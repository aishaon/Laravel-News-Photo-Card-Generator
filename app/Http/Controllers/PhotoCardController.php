<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\ImageManager;

class PhotoCardController extends Controller
{
    protected $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver);
    }

    protected $cardWidth = 1200;

    protected $cardHeight = 630;

    public function index()
    {
        return view('photo-card.index');
    }

    public function preview(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'brand_logo' => 'nullable|image|mimes:png,jpeg,svg|max:1024',
            'brand_name' => 'nullable|string|max:50',
            'template' => 'nullable|in:standard,fullwidth,split-left,split-right,minimal',
            'font' => 'nullable|in:noto,bangla,roboto,poppins',
            'overlay_color' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'overlay_opacity' => 'nullable|integer|min:0|max:100',
            'logo_position' => 'nullable|in:top-left,top-right,bottom-left,bottom-right,center',
            'text_position' => 'nullable|in:bottom,center,top',
        ]);

        try {
            $image = $this->createCard($request);

            return response($image->encode(new PngEncoder))
                ->header('Content-Type', 'image/png');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to generate preview: '.$e->getMessage(),
            ], 500);
        }
    }

    public function generate(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'brand_logo' => 'nullable|image|mimes:png,jpeg,svg|max:1024',
            'brand_name' => 'nullable|string|max:50',
            'template' => 'nullable|in:standard,fullwidth,split-left,split-right,minimal',
            'font' => 'nullable|in:noto,bangla,roboto,poppins',
            'overlay_color' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'overlay_opacity' => 'nullable|integer|min:0|max:100',
            'logo_position' => 'nullable|in:top-left,top-right,bottom-left,bottom-right,center',
            'text_position' => 'nullable|in:bottom,center,top',
        ]);

        $image = $this->createCard($request);

        $filename = 'card_'.time().'_'.uniqid().'.jpg';
        $path = 'news-cards/'.$filename;
        $jpegData = $image->encode(new JpegEncoder(90));

        Storage::disk('public')->put($path, $jpegData);
        file_put_contents(public_path('storage/'.$path), $jpegData);

        $url = '/storage/'.$path;

        return response()->json([
            'status' => 'success',
            'image_url' => $url,
            'message' => 'Card generated successfully!',
        ]);
    }

    protected function createCard(Request $request)
    {
        $uploadedImage = $this->manager->decode($request->file('image')->getPathname());
        $card = $this->manager->createImage($this->cardWidth, $this->cardHeight);

        $template = $request->input('template', 'standard');

        $uploadedImage->resize($this->cardWidth, $this->cardHeight, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        switch ($template) {
            case 'fullwidth':
                $this->insertFullWidth($card, $uploadedImage, $request);
                break;
            case 'split-left':
                $this->insertSplitLeft($card, $uploadedImage, $request);
                break;
            case 'split-right':
                $this->insertSplitRight($card, $uploadedImage, $request);
                break;
            case 'minimal':
                $this->insertMinimal($card, $uploadedImage, $request);
                break;
            default:
                $this->insertStandard($card, $uploadedImage, $request);
        }

        return $card;
    }

    protected function insertStandard($card, $image, Request $request)
    {
        $centerX = intval(($this->cardWidth - $image->width()) / 2);
        $centerY = intval(($this->cardHeight - $image->height()) / 2);
        $card->insert($image, $centerX, $centerY);

        $this->addGradientOverlay($card, $request);
        $this->addBrandLogo($card, $request);
        $this->addBrandName($card, $request);
        $this->addTitle($card, $request);
    }

    protected function insertFullWidth($card, $image, Request $request)
    {
        $card->insert($image, 0, 0);
        $this->addGradientOverlay($card, $request, 0.6);
        $this->addBrandLogo($card, $request);
        $this->addBrandName($card, $request);
        $this->addTitle($card, $request);
    }

    protected function insertSplitLeft($card, $image, Request $request)
    {
        $halfWidth = intval($this->cardWidth / 2);
        $card->insert($image, 0, 0);

        $splitOverlay = $this->manager->createImage($halfWidth, $this->cardHeight);
        $color = $request->input('overlay_color', '#1a1a2e');
        $opacity = intval($request->input('overlay_opacity', 85)) / 100;
        $r = hexdec(substr($color, 1, 2));
        $g = hexdec(substr($color, 3, 2));
        $b = hexdec(substr($color, 5, 2));
        $splitOverlay->fill("rgba($r,$g,$b,".round($opacity * 0.95).')');
        $card->insert($splitOverlay, 0, 0);

        $this->addBrandLogo($card, $request);
        $this->addBrandName($card, $request);
        $request->merge(['text_position' => 'center']);
        $this->addTitle($card, $request);
    }

    protected function insertSplitRight($card, $image, Request $request)
    {
        $halfWidth = intval($this->cardWidth / 2);
        $card->insert($image, $halfWidth, 0);

        $splitOverlay = $this->manager->createImage($halfWidth, $this->cardHeight);
        $color = $request->input('overlay_color', '#1a1a2e');
        $opacity = intval($request->input('overlay_opacity', 85)) / 100;
        $r = hexdec(substr($color, 1, 2));
        $g = hexdec(substr($color, 3, 2));
        $b = hexdec(substr($color, 5, 2));
        $splitOverlay->fill("rgba($r,$g,$b,".round($opacity * 0.95).')');
        $card->insert($splitOverlay, 0, 0);

        $this->addBrandLogo($card, $request);
        $this->addBrandName($card, $request);
        $request->merge(['text_position' => 'center']);
        $this->addTitle($card, $request);
    }

    protected function insertMinimal($card, $image, Request $request)
    {
        $centerX = intval(($this->cardWidth - $image->width()) / 2);
        $centerY = intval(($this->cardHeight - $image->height()) / 2);
        $card->insert($image, $centerX, $centerY);

        $overlayHeight = 120;
        $overlay = $this->manager->createImage($this->cardWidth, $overlayHeight);
        $color = $request->input('overlay_color', '#000000');
        $opacity = intval($request->input('overlay_opacity', 70)) / 100;
        $r = hexdec(substr($color, 1, 2));
        $g = hexdec(substr($color, 3, 2));
        $b = hexdec(substr($color, 5, 2));
        $overlay->fill("rgba($r,$g,$b,".round($opacity * 0.95).')');
        $card->insert($overlay, 0, $this->cardHeight - $overlayHeight);

        $this->addBrandLogo($card, $request);
        $this->addBrandName($card, $request);
        $request->merge(['text_position' => 'bottom']);
        $this->addTitle($card, $request, 0.7);
    }

    protected function addGradientOverlay($image, Request $request, $startRatio = 0.5)
    {
        $startY = intval($this->cardHeight * $startRatio);
        $gradientHeight = $this->cardHeight - $startY;

        $color = $request->input('overlay_color', '#000000');
        $opacity = intval($request->input('overlay_opacity', 70)) / 100;

        $r = hexdec(substr($color, 1, 2));
        $g = hexdec(substr($color, 3, 2));
        $b = hexdec(substr($color, 5, 2));

        $blockSize = 10;
        for ($y = 0; $y < $gradientHeight; $y += $blockSize) {
            $actualBlockSize = min($blockSize, $gradientHeight - $y);
            $ratio = $y / $gradientHeight;
            $alpha = round($opacity * $ratio * 0.95, 2);
            $overlay = $this->manager->createImage($this->cardWidth, $actualBlockSize);
            $overlay->fill("rgba($r,$g,$b,$alpha)");
            $image->insert($overlay, 0, $startY + $y);
        }
    }

    protected function addBrandLogo($image, Request $request)
    {
        $logoPath = null;
        $logoSize = 80;

        if ($request->hasFile('brand_logo')) {
            $logoPath = $request->file('brand_logo')->getPathname();
            $logoSize = 60;
        } else {
            $defaultLogoPath = public_path('logo.png');
            if (file_exists($defaultLogoPath)) {
                $logoPath = $defaultLogoPath;
            }
        }

        if ($logoPath) {
            $logo = $this->manager->decodePath($logoPath);
            $logo->resize($logoSize, $logoSize);

            $position = $request->input('logo_position', 'top-left');
            $margin = 30;

            switch ($position) {
                case 'top-right':
                    $x = $this->cardWidth - $logoSize - $margin;
                    $y = $margin;
                    break;
                case 'bottom-left':
                    $x = $margin;
                    $y = $this->cardHeight - $logoSize - $margin;
                    break;
                case 'bottom-right':
                    $x = $this->cardWidth - $logoSize - $margin;
                    $y = $this->cardHeight - $logoSize - $margin;
                    break;
                case 'center':
                    $x = intval(($this->cardWidth - $logoSize) / 2);
                    $y = intval(($this->cardHeight - $logoSize) / 2);
                    break;
                default:
                    $x = $margin;
                    $y = $margin;
            }

            $image->insert($logo, $x, $y);
        }
    }

    protected function addBrandName($image, Request $request)
    {
        $brandName = $request->input('brand_name');

        if (empty($brandName)) {
            return;
        }

        $fontPath = public_path('fonts/NotoSans-Bold.ttf');
        if (! file_exists($fontPath)) {
            return;
        }

        $fontSize = 18;
        $position = $request->input('logo_position', 'top-left');
        $margin = 30;
        $logoSize = $request->hasFile('brand_logo') ? 60 : 80;
        $spacing = 15;

        $x = $margin;
        $y = $margin;

        switch ($position) {
            case 'top-right':
                $x = $this->cardWidth - $margin;
                $y = $margin + intval($logoSize / 2);
                $textX = $x - 200;
                break;
            case 'bottom-left':
                $x = $margin;
                $y = $this->cardHeight - $margin;
                $textX = $x + $logoSize + $spacing;
                break;
            case 'bottom-right':
                $x = $this->cardWidth - $margin;
                $y = $this->cardHeight - $margin;
                $textX = $x - 200;
                break;
            case 'center':
                $x = intval(($this->cardWidth - $logoSize) / 2);
                $y = intval(($this->cardHeight - $logoSize) / 2);
                $textX = $x + $logoSize + $spacing;
                $y = $y + intval($logoSize / 2);
                break;
            default:
                $textX = $x + $logoSize + $spacing;
                $y = $y + intval($logoSize / 2);
        }

        $bbox = imagettfbbox($fontSize, 0, $fontPath, $brandName);
        $textWidth = $bbox[2] - $bbox[0];
        $textHeight = $bbox[1] - $bbox[7];
        $textY = $y + intval($textHeight / 2);

        if (in_array($position, ['top-right', 'bottom-right'])) {
            $textX = $x - $textWidth - 10;
        }

        $image->text($brandName, $textX + 1, $textY + 1, function ($font) use ($fontSize, $fontPath) {
            $font->file($fontPath);
            $font->size($fontSize);
            $font->color('rgba(0,0,0,0.3)');
        });

        $image->text($brandName, $textX, $textY, function ($font) use ($fontSize, $fontPath) {
            $font->file($fontPath);
            $font->size($fontSize);
            $font->color('#ffffff');
        });
    }

    protected function addTitle($image, Request $request, $fontScale = 1.0)
    {
        $title = $request->input('title');
        $selectedFont = $request->input('font', $this->detectFont($title));

        $fontPath = $this->getFontPath($selectedFont, $title);

        $baseFontSize = $this->calculateFontSize($title);
        $fontSize = intval($baseFontSize * $fontScale);

        $textWidth = $this->cardWidth - 100;

        $position = $request->input('text_position', 'bottom');

        switch ($position) {
            case 'top':
                $x = 50;
                $y = 80;
                break;
            case 'center':
                $x = 50;
                $y = intval(($this->cardHeight) / 2);
                break;
            default:
                $x = 50;
                $y = $this->cardHeight - 60;
        }

        $wrappedText = $this->wrapText($title, $textWidth, $fontSize, $fontPath);
        $lines = explode("\n", $wrappedText);
        $lineHeight = $fontSize * 1.4;

        if ($position === 'center') {
            $totalTextHeight = count($lines) * $lineHeight;
            $y = intval(($this->cardHeight - $totalTextHeight) / 2);
        } elseif ($position === 'bottom') {
            $totalTextHeight = count($lines) * $lineHeight;
            $y = $this->cardHeight - $totalTextHeight - 40;
        }

        $shadowOffset = 2;
        foreach ($lines as $line) {
            $image->text($line, $x + $shadowOffset, $y + $shadowOffset, function ($font) use ($fontSize, $fontPath) {
                $font->file($fontPath);
                $font->size($fontSize);
                $font->color('rgba(0,0,0,0.5)');
                $font->align('left', 'top');
            });

            $image->text($line, $x, $y, function ($font) use ($fontSize, $fontPath) {
                $font->file($fontPath);
                $font->size($fontSize);
                $font->color('#ffffff');
                $font->align('left', 'top');
            });

            $y += $lineHeight;
        }
    }

    protected function detectFont($text)
    {
        $banglaPattern = '/[\x{0980}-\x{09FF}]/u';

        return preg_match($banglaPattern, $text) > 0 ? 'bangla' : 'noto';
    }

    protected function getFontPath($font, $title = '')
    {
        $isBangla = $this->isBanglaText($title);

        if ($isBangla || $font === 'bangla') {
            $path = public_path('fonts/NotoSansBengali-Bold.ttf');
            if (file_exists($path)) {
                return $path;
            }
        }

        switch ($font) {
            case 'roboto':
                $path = public_path('fonts/Roboto-Bold.ttf');
                if (file_exists($path)) {
                    return $path;
                }
                break;
            case 'poppins':
                $path = public_path('fonts/Poppins-Bold.ttf');
                if (file_exists($path)) {
                    return $path;
                }
                break;
        }

        return public_path('fonts/NotoSans-Bold.ttf');
    }

    protected function isBanglaText($text)
    {
        $banglaPattern = '/[\x{0980}-\x{09FF}]/u';

        return preg_match($banglaPattern, $text) > 0;
    }

    protected function calculateFontSize($text)
    {
        $length = mb_strlen($text);

        if ($length <= 30) {
            return 48;
        } elseif ($length <= 50) {
            return 40;
        } elseif ($length <= 80) {
            return 32;
        } elseif ($length <= 120) {
            return 26;
        } else {
            return 22;
        }
    }

    protected function wrapText($text, $maxWidth, $fontSize, $fontPath)
    {
        $words = preg_split('/\s+/u', $text);
        $lines = [];
        $currentLine = '';

        foreach ($words as $word) {
            $testLine = $currentLine ? $currentLine.' '.$word : $word;
            $bbox = imagettfbbox($fontSize, 0, $fontPath, $testLine);
            $textWidth = $bbox[2] - $bbox[0];

            if ($textWidth <= $maxWidth) {
                $currentLine = $testLine;
            } else {
                if ($currentLine) {
                    $lines[] = $currentLine;
                }
                $currentLine = $word;
            }
        }

        if ($currentLine) {
            $lines[] = $currentLine;
        }

        return implode("\n", $lines);
    }

    public function apiGenerate(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'brand_logo' => 'nullable|image|mimes:png,jpeg,svg|max:1024',
            'brand_name' => 'nullable|string|max:50',
            'template' => 'nullable|in:standard,fullwidth,split-left,split-right,minimal',
            'font' => 'nullable|in:noto,bangla,roboto,poppins',
            'overlay_color' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'overlay_opacity' => 'nullable|integer|min:0|max:100',
            'logo_position' => 'nullable|in:top-left,top-right,bottom-left,bottom-right,center',
            'text_position' => 'nullable|in:bottom,center,top',
        ]);

        $image = $this->createCard($request);

        $filename = 'card_'.time().'_'.uniqid().'.jpg';
        $path = 'news-cards/'.$filename;
        $jpegData = $image->encode(new JpegEncoder(90));

        Storage::disk('public')->put($path, $jpegData);
        file_put_contents(public_path('storage/'.$path), $jpegData);

        $url = '/storage/'.$path;

        return response()->json([
            'status' => 'success',
            'image_url' => $url,
        ]);
    }
}

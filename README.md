# News Photo Card Generator

A modern, production-ready Photo Card Generator system for News Portals with Bangla and English font support.

## Features

- ✅ Dynamic image generation with Intervention Image
- ✅ Bangla + English font support (auto-detection)
- ✅ Modern UI with Tailwind CSS
- ✅ Preview button (instant image render)
- ✅ Generate & Save button (store image)
- ✅ Social media optimized dimensions (1200x630)
- ✅ Gradient overlay for text readability
- ✅ Dynamic font resizing based on title length
- ✅ Text shadow for better visibility
- ✅ Optional logo placement
- ✅ API endpoint for programmatic use

## Requirements

- PHP 8.1+
- Laravel 11.x
- GD Library (for image processing)
- Composer

## Installation

1. Clone the repository:
```bash
git clone <repository-url>
cd laravel-news-photo-card
```

2. Install dependencies:
```bash
composer install
```

3. Generate application key:
```bash
php artisan key:generate
```

4. Create storage link:
```bash
php artisan storage:link
```

5. Ensure fonts are in place:
   - `public/fonts/NotoSans-Bold.ttf` (for English)
   - `public/fonts/NotoSansBengali-Bold.ttf` (for Bangla)

6. Add a logo file:
   - Place your logo at `public/logo.png`

7. Run the development server:
```bash
php artisan serve
```

## Usage

### Web Interface

1. Open your browser and navigate to: `http://localhost:8000/photo-card`
2. Enter your news title (Bangla or English)
3. Upload a background image
4. Click "Preview" to see the card instantly
5. Click "Generate & Save" to save the card to storage

### API Endpoint

**POST** `/api/photo-card`

**Parameters:**
- `title` (string, required): The news title (max 200 chars)
- `image` (file, required): Background image (jpeg, png, jpg)

**Response:**
```json
{
    "status": "success",
    "image_url": "http://localhost:8000/storage/news-cards/card_1234567890_abc123.jpg"
}
```

**Example with cURL:**
```bash
curl -X POST http://localhost:8000/api/photo-card \
  -F "title=আজকের সংবাদ" \
  -F "image=@/path/to/image.jpg" \
  -H "Accept: application/json"
```

## Card Specifications

- **Dimensions**: 1200x630 pixels (social media optimized)
- **Background**: User-uploaded image
- **Overlay**: Gradient from transparent to black (0.7 opacity at bottom)
- **Font Size**: Dynamic (22-48px based on text length)
- **Text Alignment**: Center, left-aligned within card
- **Logo**: Top-left corner (80x80px)
- **Output Format**: JPEG (quality 90%)

## Directory Structure

```
public/
├── fonts/
│   ├── NotoSans-Bold.ttf
│   └── NotoSansBengali-Bold.ttf
└── logo.png

storage/app/public/
└── news-cards/
    └── [generated cards]
```

## Routes

| Method | Route | Description |
|--------|-------|-------------|
| GET | `/photo-card` | Main UI page |
| POST | `/photo-card/preview` | Generate preview image |
| POST | `/photo-card/generate` | Generate and save card |
| POST | `/api/photo-card` | API endpoint for card generation |

## Customization

### Font Configuration

To change fonts, update the controller at:
`app/Http/Controllers/PhotoCardController.php`

```php
protected function addTitle($image, $title)
{
    // Change font path here
    $fontPath = public_path('fonts/YourFont-Bold.ttf');
}
```

### Card Dimensions

Modify the protected properties:
```php
protected $cardWidth = 1200;
protected $cardHeight = 630;
```

### Logo Configuration

Update the `addLogo()` method to customize logo size and position:
```php
protected function addLogo($image)
{
    $logo = Image::make(public_path('logo.png'));
    $logo->resize(100, 100); // Customize size
    $image->insert($logo, 'top-left', 30, 30); // Customize position
}
```

## Troubleshooting

### Image not displaying?
- Ensure storage link is created: `php artisan storage:link`
- Check storage permissions
- Verify image format (jpeg, png, jpg)

### Font not working?
- Ensure font files exist in `public/fonts/`
- Verify font file is valid TrueType font
- Check PHP GD library is installed

### Bangla text not rendering correctly?
- Ensure NotoSansBengali font is installed
- Verify font file is valid
- Check browser encoding is UTF-8

## API Example (JavaScript)

```javascript
const formData = new FormData();
formData.append('title', 'আজকের সংবাদ');
formData.append('image', imageFile);

const response = await fetch('/api/photo-card', {
    method: 'POST',
    body: formData,
    headers: {
        'X-CSRF-TOKEN': csrfToken
    }
});

const data = await response.json();
console.log(data.image_url);
```

## License

MIT License - Feel free to use this project for personal or commercial purposes.

## Support

For issues or questions, please create an issue in the repository.

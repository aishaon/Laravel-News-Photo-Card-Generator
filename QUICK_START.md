# Photo Card Generator - Quick Start Guide

## ✅ System Status: READY

All components are installed and configured correctly.

## Quick Test

### 1. Start the Server

```bash
php artisan serve --host=localhost --port=8000
```

### 2. Open in Browser

Navigate to: **http://localhost:8000/photo-card**

Or simply: **http://localhost:8000/**

### 3. Test the System

1. **Enter a Title**: Try "আজকের সংবাদ" (Bangla) or "Breaking News" (English)
2. **Upload an Image**: Click the upload area or drag & drop an image
3. **Click Preview**: Click the blue "Preview" button
4. **Generate & Save**: Click the green "Generate & Save" button

## What's Working

✅ **Bangla + English Font Support** - Automatically detects and renders both languages
✅ **Image Upload** - Drag & drop or click to upload
✅ **Preview Generation** - Instant preview without saving
✅ **Card Generation** - Saves to storage and returns download URL
✅ **Modern UI** - Tailwind CSS styling with responsive design
✅ **Social Media Optimized** - 1200x630 dimensions perfect for sharing

## API Testing

Test the API directly:

```bash
curl -X POST http://localhost:8000/api/photo-card \
  -F "title=আজকের খবর" \
  -F "image=@/path/to/image.jpg"
```

## File Structure

```
✓ app/Http/Controllers/PhotoCardController.php
✓ resources/views/photo-card/index.blade.php
✓ public/fonts/NotoSans-Bold.ttf
✓ public/fonts/NotoSansBengali-Bold.ttf
✓ public/logo.png
✓ storage/app/public/news-cards/
✓ public/storage -> storage/app/public (symlink)
```

## Troubleshooting

### If Preview Fails

1. Check browser console for errors (F12)
2. Check Laravel logs: `storage/logs/laravel.log`
3. Ensure image is valid (jpeg, png, jpg)
4. Ensure image size is under 5MB

### If Fonts Don't Display

1. Verify font files exist: `ls public/fonts/`
2. Check browser encoding is UTF-8
3. Try both Bangla and English text

### If Storage Fails

1. Verify storage link: `php artisan storage:link`
2. Check permissions: storage/app/public should be writable

## Preview vs Generate

**Preview Button (Blue)**
- Generates image temporarily
- Shows in modal
- NOT saved to disk
- Perfect for testing

**Generate & Save Button (Green)**
- Saves to storage/app/public/news-cards/
- Returns downloadable URL
- Permanently stores the card

## Customization

### Change Card Size
Edit `app/Http/Controllers/PhotoCardController.php`:
```php
protected $cardWidth = 1200;
protected $cardHeight = 630;
```

### Change Logo
Replace `public/logo.png` with your own logo

### Change Fonts
Replace the .ttf files in `public/fonts/`

## Support

For issues, check:
1. Laravel logs: `storage/logs/laravel.log`
2. PHP error logs
3. Browser developer console

---

**Ready to use!** 🚀

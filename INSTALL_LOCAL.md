# Install Dependencies Locally

## ✅ Already Installed:
- ✅ DomPDF (via Composer)
- ✅ Spatie Laravel PDF
- ✅ Intervention Image

## ❌ Missing:
- ❌ Imagick PHP Extension

## Install Imagick on macOS:

### Option 1: Using Homebrew (Recommended)

```bash
# Install ImageMagick
brew install imagemagick

# Install Imagick PHP extension
pecl install imagick

# Add to php.ini (find your php.ini location first)
php --ini

# Then add this line to php.ini:
# extension=imagick.so

# Restart PHP-FPM or your web server
```

### Option 2: Using MacPorts

```bash
sudo port install ImageMagick
sudo pecl install imagick
```

### Option 3: Manual Installation

1. Download ImageMagick from: https://imagemagick.org/script/download.php
2. Install ImageMagick
3. Install Imagick extension:
   ```bash
   pecl install imagick
   ```

## Verify Installation:

```bash
# Check if Imagick is loaded
php -m | grep imagick

# Or test with PHP
php -r "echo extension_loaded('imagick') ? 'Imagick: INSTALLED ✅' : 'Imagick: NOT INSTALLED ❌';"
```

## After Installation:

1. **Restart your local server:**
   ```bash
   # Stop current server (Ctrl+C)
   # Then restart
   php artisan serve
   ```

2. **Test the image generation:**
   ```
   http://localhost:8000/test-generate-image
   ```

## If Imagick Installation Fails:

You can test without Imagick by temporarily modifying the code to skip the PDF-to-image conversion and just generate the PDF. However, for WhatsApp image sending, Imagick is required.

## Quick Test Commands:

```bash
# Check all dependencies
composer show | grep -E "dompdf|spatie"
php -m | grep imagick

# Test image generation route
curl http://localhost:8000/test-generate-image -o test-image.png
file test-image.png
```

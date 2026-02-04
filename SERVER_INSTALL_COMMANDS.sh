#!/bin/bash

# Laravel Spatie PDF Installation Commands for Hostinger Server
# Run these commands in your server's terminal/SSH

echo "=== Installing Laravel Spatie PDF Requirements ==="

# 1. Navigate to your Laravel project directory
cd /path/to/your/laravel-project

# 2. Check if Node.js is installed
echo "Checking Node.js installation..."
node --version || echo "Node.js not found. Installing..."

# 3. Install Node.js (if not installed)
# For Hostinger, you might need to use nvm or install via package manager
# Option A: Using nvm (recommended)
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash
source ~/.bashrc
nvm install 18  # or latest LTS version
nvm use 18

# Option B: If Node.js is already installed via package manager, skip above

# 4. Verify Node.js and npm
echo "Node.js version:"
node --version
echo "npm version:"
npm --version

# 5. Install Puppeteer (required for Spatie PDF)
echo "Installing Puppeteer..."
npm install puppeteer

# 6. Install additional dependencies if needed
npm install

# 7. Create storage link (if not already created)
php artisan storage:link

# 8. Clear and cache config
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# 9. Set proper permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# 10. Update .env file (add these if not present)
# LARAVEL_PDF_NODE_MODULES_PATH=/path/to/your/project/node_modules
# Or leave it default to use: base_path('node_modules')

echo ""
echo "=== Installation Complete ==="
echo "Verify installation by testing PDF generation:"
echo "Visit: https://yourdomain.com/test-pdf-report"

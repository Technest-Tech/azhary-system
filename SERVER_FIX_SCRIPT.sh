#!/bin/bash
# Complete Server Fix Script for Hostinger
# Run this on the server: bash SERVER_FIX_SCRIPT.sh

set -e  # Exit on error

echo "=========================================="
echo "Hostinger Server Fix Script"
echo "=========================================="

cd /home/u221047993/domains/azharyfr.com/public_html/manage

echo ""
echo "=== Step 1: Resolving Git Conflicts ==="
git rebase --abort 2>/dev/null || git merge --abort 2>/dev/null || echo "No rebase/merge to abort"
git stash save "Backup before pull $(date)" 2>/dev/null || echo "No changes to stash"

# Try to pull
if ! git pull origin main; then
    echo "Merge conflicts detected. Resolving..."
    git checkout --theirs composer.json composer.lock 2>/dev/null || echo "Files may not exist"
    git add composer.json composer.lock 2>/dev/null || true
    git commit -m "Resolve merge conflicts - accept remote version" 2>/dev/null || echo "No commit needed"
    git pull origin main
fi

git stash pop 2>/dev/null || echo "No stashed changes to restore"

echo ""
echo "=== Step 2: Fixing Permissions ==="
chmod -R 755 storage bootstrap/cache
chmod -R 755 public
chmod 644 public/.htaccess public/index.php 2>/dev/null || echo "Some files may not exist"

echo ""
echo "=== Step 3: Creating Required Directories ==="
mkdir -p public/reports
mkdir -p storage/app/public/reports
chmod -R 755 public/reports storage/app/public/reports

echo ""
echo "=== Step 4: Creating Storage Symlink ==="
cd public
if [ -L storage ]; then
    rm storage
fi
ln -s ../storage/app/public storage 2>/dev/null || echo "Symlink may already exist"
cd ..
ls -la public/storage | head -1

echo ""
echo "=== Step 5: Installing Composer Dependencies ==="
composer install --no-dev --optimize-autoloader --no-interaction

echo ""
echo "=== Step 6: Setting up Node.js ==="
# Find Node.js
NODE_PATH=""
if [ -f ~/nodejs/bin/node ]; then
    NODE_PATH=~/nodejs/bin/node
elif [ -f /usr/local/bin/node ]; then
    NODE_PATH=/usr/local/bin/node
elif [ -f /usr/bin/node ]; then
    NODE_PATH=/usr/bin/node
else
    NODE_PATH=$(which node 2>/dev/null || find ~ -name node -type f 2>/dev/null | head -1)
fi

if [ ! -z "$NODE_PATH" ] && [ -f "$NODE_PATH" ]; then
    echo "Node.js found at: $NODE_PATH"
    NODE_VERSION=$($NODE_PATH --version 2>/dev/null || echo "unknown")
    echo "Node.js version: $NODE_VERSION"
    
    # Update .env file
    if [ -f .env ]; then
        # Remove old entries
        sed -i '/LARAVEL_PDF_NODE_BINARY/d' .env
        sed -i '/LARAVEL_PDF_NPM_BINARY/d' .env
        
        # Add new entries
        echo "" >> .env
        echo "# Laravel PDF Node.js Configuration" >> .env
        echo "LARAVEL_PDF_NODE_BINARY=$NODE_PATH" >> .env
        
        # Find npm
        NPM_PATH=$(dirname $NODE_PATH)/npm
        if [ -f "$NPM_PATH" ]; then
            echo "LARAVEL_PDF_NPM_BINARY=$NPM_PATH" >> .env
        fi
        
        echo "Updated .env with Node.js paths"
    fi
else
    echo "WARNING: Node.js not found! You may need to install it."
    echo "Installation instructions:"
    echo "  cd ~"
    echo "  wget https://nodejs.org/dist/v20.11.0/node-v20.11.0-linux-x64.tar.gz"
    echo "  tar -xzf node-v20.11.0-linux-x64.tar.gz"
    echo "  mv node-v20.11.0-linux-x64 nodejs"
    echo "  echo 'export PATH=\$HOME/nodejs/bin:\$PATH' >> ~/.bashrc"
    echo "  source ~/.bashrc"
fi

echo ""
echo "=== Step 7: Installing Puppeteer ==="
if [ ! -d "node_modules/puppeteer" ]; then
    if command -v npm &> /dev/null; then
        echo "Installing Puppeteer..."
        npm install puppeteer --no-save
    else
        echo "WARNING: npm not found. Cannot install Puppeteer."
    fi
else
    echo "Puppeteer already installed"
fi

echo ""
echo "=== Step 8: Clearing Laravel Caches ==="
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan optimize:clear 2>/dev/null || echo "optimize:clear not available"

echo ""
echo "=== Step 9: Verifying Setup ==="
echo "Node.js: $(which node || echo 'NOT FOUND')"
echo "npm: $(which npm || echo 'NOT FOUND')"
echo "Puppeteer: $([ -d node_modules/puppeteer ] && echo 'INSTALLED' || echo 'NOT FOUND')"
echo "Storage symlink: $(ls -la public/storage 2>/dev/null | grep -o '->.*' || echo 'MISSING')"
echo "Reports directory: $([ -d public/reports ] && echo 'EXISTS' || echo 'MISSING')"

echo ""
echo "=== Step 10: Testing Laravel ==="
php artisan route:list --columns=uri,method | head -5 || echo "Route list failed"

echo ""
echo "=========================================="
echo "Setup Complete!"
echo "=========================================="
echo ""
echo "Next steps:"
echo "1. Check .env file for LARAVEL_PDF_NODE_BINARY"
echo "2. Test: https://manage.azharyfr.com/test-generate-image"
echo "3. Check logs: tail -50 storage/logs/laravel.log"
echo ""

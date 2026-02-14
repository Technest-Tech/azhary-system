#!/bin/bash
# Quick fix - copy and paste this entire block into SSH

cd /home/u221047993/domains/azharyfr.com/public_html/manage && \
git rebase --abort 2>/dev/null; git merge --abort 2>/dev/null; git stash; \
git pull origin main || (git checkout --theirs composer.json composer.lock 2>/dev/null; git add .; git commit -m "fix" 2>/dev/null; git pull origin main); \
chmod -R 755 storage bootstrap/cache public; \
mkdir -p public/reports storage/app/public/reports; \
chmod -R 755 public/reports; \
cd public && rm -f storage && ln -s ../storage/app/public storage && cd ..; \
composer install --no-dev --optimize-autoloader --no-interaction; \
NODE=$(which node || find ~ -name node -type f 2>/dev/null | head -1); \
if [ ! -z "$NODE" ]; then echo "LARAVEL_PDF_NODE_BINARY=$NODE" >> .env; fi; \
[ ! -d node_modules/puppeteer ] && npm install puppeteer 2>/dev/null || true; \
php artisan config:clear && php artisan cache:clear && php artisan view:clear; \
echo "✅ Done! Node: $NODE"

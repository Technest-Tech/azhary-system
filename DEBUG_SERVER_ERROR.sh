#!/bin/bash

# Commands to diagnose the WhatsApp test error on server
# Run these commands on your Hostinger server

echo "=== 1. Check the actual error message from log ==="
echo "tail -100 storage/logs/laravel.log | grep -A 5 -B 5 'Image generation failed\|Exception\|Error' | head -50"
echo ""

echo "=== 2. Check if Node.js is installed and accessible ==="
echo "which node"
echo "node --version"
echo ""

echo "=== 3. Check if npm is available ==="
echo "which npm"
echo "npm --version"
echo ""

echo "=== 4. Check if Puppeteer is installed ==="
echo "ls -la node_modules/puppeteer 2>/dev/null || echo 'Puppeteer not found in node_modules'"
echo ""

echo "=== 5. Check Browsershot configuration ==="
echo "cat config/laravel-pdf.php | grep node_modules"
echo ""

echo "=== 6. Check storage permissions ==="
echo "ls -la storage/app/public/reports/ 2>/dev/null || echo 'Reports directory does not exist'"
echo ""

echo "=== 7. Test if Browsershot can find Puppeteer ==="
echo "php artisan tinker --execute=\"echo \\Spatie\\Browsershot\\Browsershot::html('<html><body>Test</body></html>')->screenshot();\""
echo ""

echo "=== 8. Check Laravel config cache ==="
echo "php artisan config:clear"
echo "php artisan cache:clear"
echo ""

echo "=== 9. Check if reports directory exists and is writable ==="
echo "mkdir -p storage/app/public/reports"
echo "chmod -R 775 storage/app/public/reports"
echo ""

echo "=== 10. View full recent errors ==="
echo "tail -200 storage/logs/laravel.log | grep -E 'ERROR|Exception|Failed' -A 10 | tail -100"

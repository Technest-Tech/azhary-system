# Fix Storage File Access on Hostinger

## Problem
HTTP 422 error when accessing storage files via URL. The root `.htaccess` needs to allow direct access to storage files.

## Solution

Update your root `.htaccess` file (in the project root, not in public folder) to:

```apache
<IfModule mod_rewrite.c>
    Options +FollowSymLinks
    RewriteEngine On

    # Allow direct access to storage files (bypass Laravel routing)
    RewriteCond %{REQUEST_URI} ^/storage/
    RewriteRule ^storage/(.*)$ public/storage/$1 [L]

    # Allow direct access to public files
    RewriteCond %{REQUEST_URI} ^/public/
    RewriteCond %{REQUEST_FILENAME} -f
    RewriteRule ^public/(.*)$ public/$1 [L]

    # Redirect everything else to public folder
    RewriteCond %{REQUEST_URI} !^/public/ 
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ /public/$1 [L]
    
    # Handle root requests
    RewriteRule ^(/)?$ public/index.php [L] 
</IfModule>
```

## Steps to Apply

1. **Backup current .htaccess:**
   ```bash
   cp .htaccess .htaccess.backup
   ```

2. **Update .htaccess with the new content above**

3. **Verify storage symlink:**
   ```bash
   ls -la public/storage
   # Should show: storage -> ../storage/app/public
   ```

4. **Set proper permissions:**
   ```bash
   chmod -R 755 storage/app/public/reports/
   chmod 644 storage/app/public/reports/*.png
   ```

5. **Test file access:**
   ```bash
   curl -I https://manage.azharyfr.com/storage/reports/rapport-cours-999-1770297070.png
   # Should return HTTP 200, not 422
   ```

6. **Clear caches:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

## Alternative: If .htaccess doesn't work

If the above doesn't work, you can save files directly to `public/reports/` instead:

1. Create directory:
   ```bash
   mkdir -p public/reports
   chmod -R 755 public/reports
   ```

2. Update `WhatsAppService.php` to save to `public/reports/` instead of `storage/app/public/reports/`

# Fix /tmp File Error - Company Registration

## Error
```
FileNotFoundException: The file "/tmp/phpsgUUBy" does not exist
HTTP 500 Internal Server Error
```

## Root Cause
Laravel is trying to compile Blade views and create temporary files in `/tmp`, but the web server user doesn't have write permissions to `/tmp` or the directory is not accessible.

## ⚠️ IMMEDIATE FIX - Run on Server

**SSH into your server and run these commands:**

```bash
# 1. Connect to server
ssh phyziolinegit@147.93.85.27

# 2. Navigate to project
cd /home/phyziolinegit/htdocs/phyzioline.com

# 3. Clear ALL caches (CRITICAL!)
php artisan view:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# 4. Delete ALL compiled views
rm -rf storage/framework/views/*

# 5. Create alternative temp directory in storage
mkdir -p storage/framework/tmp
chmod 775 storage/framework/tmp
chown phyziolinegit:phyziolinegit storage/framework/tmp

# 6. Fix storage permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chown -R phyziolinegit:phyziolinegit storage bootstrap/cache

# 7. Set PHP temp directory (if possible)
# Check PHP version and config
php -v
php -i | grep "sys_temp_dir"

# 8. Rebuild caches
php artisan view:cache
php artisan config:cache
php artisan route:cache

# 9. Test
curl -I https://phyzioline.com/en/register/company
```

## Alternative: Configure PHP to Use Custom Temp Directory

If you have access to `php.ini`:

1. Find php.ini location:
```bash
php -i | grep "Loaded Configuration File"
```

2. Edit php.ini and add:
```ini
sys_temp_dir = /home/phyziolinegit/htdocs/phyzioline.com/storage/framework/tmp
```

3. Restart PHP-FPM:
```bash
sudo systemctl restart php-fpm
# or
sudo service php8.1-fpm restart  # Adjust version
```

## Quick Fix Script

I've created `fix_tmp_error.sh` - upload to server and run:

```bash
chmod +x fix_tmp_error.sh
./fix_tmp_error.sh
```

## Verify Fix

After running commands, test:
- `https://phyzioline.com/en/register/company`
- `https://phyzioline.com/ar/register/company`

The page should load without the `/tmp` error.


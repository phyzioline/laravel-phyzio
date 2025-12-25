# Fix Company Registration Error - /tmp File Not Found

## Error
```
The file "/tmp/phpCH9PBQ" does not exist
ViewException
```

## Cause
This is a server-side permissions issue. Laravel cannot create temporary files for view compilation because:
1. `/tmp` directory doesn't have write permissions
2. Laravel's view cache directory has permission issues
3. PHP's temporary directory is not accessible

## Solution - Run on Server

SSH into your server and run these commands:

```bash
# 1. Navigate to project
cd /home/phyziolinegit/htdocs/phyzioline.com

# 2. Clear all view caches
php artisan view:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# 3. Fix storage permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# 4. Fix ownership (if needed)
chown -R www-data:www-data storage bootstrap/cache

# 5. Clear compiled views manually (if needed)
rm -rf storage/framework/views/*

# 6. Rebuild caches
php artisan view:cache
php artisan config:cache
php artisan route:cache
```

## Alternative: Fix /tmp Permissions

If the issue persists, check `/tmp` directory permissions:

```bash
# Check /tmp permissions
ls -ld /tmp

# Fix /tmp permissions (if needed - be careful!)
chmod 1777 /tmp

# Or set PHP temp directory in php.ini
# Find php.ini location:
php -i | grep "Loaded Configuration File

# Edit php.ini and set:
# sys_temp_dir = /home/phyziolinegit/htdocs/phyzioline.com/storage/framework/tmp
```

## Quick Fix Script

Create a file `fix_permissions.sh` on server:

```bash
#!/bin/bash
cd /home/phyziolinegit/htdocs/phyzioline.com
php artisan view:clear
php artisan cache:clear
rm -rf storage/framework/views/*
chmod -R 775 storage bootstrap/cache
php artisan view:cache
echo "✅ Fixed!"
```

Then run:
```bash
chmod +x fix_permissions.sh
./fix_permissions.sh
```

## Verify Fix

After running the commands, try accessing:
- `https://phyzioline.com/en/register/company`
- `https://phyzioline.com/ar/register/company`

The page should load without errors.


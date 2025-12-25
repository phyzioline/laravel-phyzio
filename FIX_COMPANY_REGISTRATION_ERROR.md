# Fix Company Registration Error - /tmp File Not Found

## Error
```
The file "/tmp/phpKRhGnx" does not exist
ViewException
HTTP 500 Internal Server Error
```

## Cause
This is a **server-side permissions issue**. Laravel cannot create temporary files for view compilation because:
1. `/tmp` directory doesn't have write permissions for the web server user
2. Laravel's view cache directory has permission issues
3. PHP's temporary directory is not accessible to the web server

## ⚠️ URGENT: Run This on Your Server NOW

**SSH into your server and run these commands EXACTLY:**

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

# 5. Fix storage permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# 6. Fix ownership (IMPORTANT!)
chown -R phyziolinegit:phyziolinegit storage bootstrap/cache

# 7. Create tmp directory in storage (alternative to /tmp)
mkdir -p storage/framework/tmp
chmod 775 storage/framework/tmp

# 8. Rebuild caches
php artisan view:cache
php artisan config:cache
php artisan route:cache

# 9. Exit
exit
```

## Quick Fix Script

I've created `fix_server_permissions.sh` - upload it to your server and run:

```bash
# On server:
chmod +x fix_server_permissions.sh
./fix_server_permissions.sh
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


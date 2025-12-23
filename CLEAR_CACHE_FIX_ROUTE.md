# Fix Route Cache Error for Shop Page

## Issue
The shop page is showing `Route [product.show] not defined` error. This is because the view cache contains the old route format.

## Solution

Run these commands on your server to clear the cache:

```bash
php artisan view:clear
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

Then rebuild the cache (if needed):

```bash
php artisan view:cache
php artisan route:cache
php artisan config:cache
```

## Alternative: Delete Cache Files Manually

If you can't run PHP commands, delete these directories/files:

1. `storage/framework/views/*` - Delete all compiled views
2. `bootstrap/cache/routes.php` - Delete route cache
3. `bootstrap/cache/config.php` - Delete config cache

Then the views will be recompiled on next request.

## Verification

After clearing cache, visit `/shop` and check that product links work correctly.


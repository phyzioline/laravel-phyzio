# Server Deployment Commands - Shop Module Update
**Date:** December 23, 2025  
**Commit:** e74d295 - Shop module improvements

## 🚀 Quick Deployment (SSH)

Connect to your server and run these commands:

```bash
# 1. Navigate to project directory
cd /home/phyziolinegit/htdocs/phyzioline.com

# 2. Pull latest changes from GitHub
git pull origin main

# 3. Install/Update dependencies (if composer.json changed)
composer install --no-dev --optimize-autoloader

# 4. Run database migration (NEW: adds verified_purchase to reviews table)
php artisan migrate --force

# 5. Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 6. Rebuild caches for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Set proper permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 8. Verify deployment
php artisan about
```

## 📋 What's Being Deployed

### New Files:
- ✅ `app/Services/DeliveryDateService.php` - Delivery date calculation
- ✅ `app/Services/FrequentlyBoughtTogetherService.php` - Cross-sell recommendations
- ✅ `database/migrations/2025_12_23_140000_add_verified_purchase_to_reviews.php` - **NEW MIGRATION**
- ✅ `SHOP_MODULE_SITUATION_ANALYSIS.md` - Documentation

### Modified Files:
- ✅ `app/Http/Controllers/Web/CartController.php` - Cart improvements
- ✅ `app/Http/Controllers/Web/OrderController.php` - Order processing
- ✅ `app/Http/Controllers/Web/ProductReviewController.php` - Review handling
- ✅ `app/Models/Review.php` - Verified purchase support
- ✅ `app/Services/Web/ShowService.php` - Product display enhancements
- ✅ `resources/views/web/pages/cart.blade.php` - Cart UI updates
- ✅ `resources/views/web/pages/showDetails.blade.php` - Product page updates
- ✅ `routes/web.php` - Route updates

## ⚠️ Important Notes

1. **Database Migration Required:** The new migration adds `verified_purchase` column to `reviews` table. This is safe and non-destructive.

2. **No Downtime Expected:** These are code updates only. The migration is simple and fast.

3. **Cache Clearing:** Essential! The route and view caches must be cleared for new routes/views to work.

4. **Backup Recommended:** Before running migration, consider backing up the `reviews` table:
   ```bash
   # If using MySQL
   mysqldump -u username -p database_name reviews > reviews_backup.sql
   ```

## 🔍 Verification Steps

After deployment, verify:

1. ✅ Product pages load correctly
2. ✅ Cart functionality works
3. ✅ Reviews display properly
4. ✅ New services are accessible
5. ✅ No errors in Laravel logs: `tail -f storage/logs/laravel.log`

## 🆘 Rollback (If Needed)

If something goes wrong:

```bash
# Rollback last migration
php artisan migrate:rollback --step=1

# Or restore from git
git reset --hard HEAD~1
git pull origin main

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

**Status:** Ready to Deploy ✅  
**Risk Level:** LOW (Simple migration, no breaking changes)


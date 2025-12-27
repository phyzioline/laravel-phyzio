# Server Update Commands - Typography & Logo Update
**Date:** December 28, 2025  
**Commit:** ca8964a - Inter font typography system and Phyzioline logo update

## 🚀 Quick Deployment (SSH)

Connect to your server and run these commands:

```bash
# 1. Navigate to project directory
cd /home/phyziolinegit/htdocs/phyzioline.com

# 2. Pull latest changes from GitHub
git pull origin main

# 3. Clear view cache (IMPORTANT - we changed Blade templates)
php artisan view:clear

# 4. Clear all caches (recommended)
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# 5. Rebuild caches for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Typography update completed successfully!"
```

## 📋 All-in-One Command Block

Copy and paste this entire block into your server SSH terminal:

```bash
cd /home/phyziolinegit/htdocs/phyzioline.com && \
git pull origin main && \
php artisan view:clear && \
php artisan cache:clear && \
php artisan config:clear && \
php artisan route:clear && \
php artisan config:cache && \
php artisan route:cache && \
php artisan view:cache && \
echo "✅ Typography update completed successfully!"
```

## 📦 What's Being Deployed

### New Files:
- ✅ `public/css/phyzioline-typography.css` - Global Inter font typography system

### Modified Files:
- ✅ `resources/views/web/layouts/dashboard_master.blade.php` - Inter font + logo
- ✅ `resources/views/dashboard/layouts/app.blade.php` - Inter font + logo
- ✅ `resources/views/web/therapist/layout.blade.php` - Inter font + logo

## ⚠️ Important Notes

1. **No Database Migration Required:** This is a UI/design update only. No database changes.

2. **View Cache Must Be Cleared:** Since we changed Blade templates, the view cache must be cleared for changes to appear.

3. **Static Assets:** The new CSS file (`phyzioline-typography.css`) will be served automatically - no special action needed.

4. **No Downtime Expected:** These are frontend changes only. The site will continue working normally.

## 🔍 Verification Steps

After deployment, verify:

1. ✅ All dashboards load with Inter font (check font-family in browser DevTools)
2. ✅ Phyzioline logo appears in dashboard sidebars (not text/icon)
3. ✅ Typography looks consistent across all dashboards
4. ✅ No console errors in browser DevTools
5. ✅ CSS file loads correctly: Check Network tab for `phyzioline-typography.css`

## 🎨 What Changed

- **Font:** All dashboards now use Inter font (weights: 400, 500, 600, 700)
- **Logo:** Replaced "Phyzioline" text and hospital icon with actual logo image
- **Typography:** Professional typography hierarchy (H1-H6, body, buttons, forms, tables)
- **Consistency:** Unified branding and typography across all dashboards

## 🆘 Rollback (If Needed)

If something goes wrong:

```bash
# Restore from git
git reset --hard HEAD~1
git pull origin main

# Clear caches
php artisan view:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

---

**Status:** Ready to Deploy ✅  
**Risk Level:** VERY LOW (UI/design changes only, no breaking changes)  
**Migration Required:** ❌ NO  
**Cache Clear Required:** ✅ YES (view cache)


# ============================================
# DEPLOYMENT CHECKLIST FOR PHYZIOLINE
# ============================================

## Files to Upload to Server

### Server Details:
- Host: 147.93.85.27
- User: phyziolinegit
- Remote Path: /home/phyziolinegit/htdocs/phyzioline.com
- Local Path: d:\laravel\phyzioline.com

---

## PHASE 1: Shop Page Files (4 files)

1. resources/views/web/layouts/footer.blade.php
2. resources/views/web/pages/index.blade.php
3. resources/views/web/pages/show.blade.php
4. resources/views/web/pages/showDetails.blade.php

---

## PHASE 2: New Dashboard Controllers (3 files)

5. app/Http/Controllers/Dashboard/InventoryController.php
6. app/Http/Controllers/Dashboard/PricingController.php
7. app/Http/Controllers/Dashboard/ReportsController.php

---

## PHASE 3: New Dashboard Views (4 files)

### Create these directories first on server:
- resources/views/dashboard/pages/inventory/
- resources/views/dashboard/pages/pricing/
- resources/views/dashboard/pages/reports/

### Then upload:
8. resources/views/dashboard/pages/inventory/manage.blade.php
9. resources/views/dashboard/pages/pricing/manage.blade.php
10. resources/views/dashboard/pages/reports/product-performance.blade.php
11. resources/views/dashboard/pages/reports/sales-dashboard.blade.php

---

## PHASE 4: Modified Files (2 files)

12. resources/views/dashboard/layouts/sidebar.blade.php
13. routes/dashboard.php

---

## PHASE 5: Clear Cache on Server

Run these commands on the server:
```bash
cd /home/phyziolinegit/htdocs/phyzioline.com
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan optimize
```

---

## Verification URLs

After deployment, test these pages:

### Customer Side:
- https://phyzioline.com/shop

### Admin Dashboard:
- https://phyzioline.com/dashboard/login
- https://phyzioline.com/dashboard/inventory/manage
- https://phyzioline.com/dashboard/pricing/manage
- https://phyzioline.com/dashboard/reports/sales-dashboard
- https://phyzioline.com/dashboard/reports/product-performance

### Login Credentials:
- Email: admin@admin.com
- Password: password

---

## Quick Upload via WinSCP (Recommended)

1. Download WinSCP: https://winscp.net/eng/download.php
2. Connect to server (SFTP, port 22)
3. Drag and drop all 13 files from local to server
4. Use terminal in WinSCP to run cache clear commands

---

## Quick Upload via CloudPanel

1. Login: https://147.93.85.27:8443
2. Files → File Manager
3. Upload each file to its location
4. Clear cache manually by deleting files in:
   - bootstrap/cache/
   - storage/framework/views/
   - storage/framework/cache/

# Enhanced Deployment Script for Phyzioline
# This script uploads ALL updated files including new dashboard features

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Phyzioline FULL Deployment Script" -ForegroundColor Cyan
Write-Host "  Server: 147.93.85.27" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Configuration
$SERVER = "147.93.85.27"
$USER = "phyziolinegit"
$REMOTE_PATH = "/home/phyziolinegit/htdocs/phyzioline.com"
$LOCAL_PATH = "d:\laravel\phyzioline.com"

Write-Host "IMPORTANT: You will need to enter your password multiple times" -ForegroundColor Yellow
Write-Host "This will deploy ALL updates including new dashboard features" -ForegroundColor Yellow
Write-Host "Press any key to continue or Ctrl+C to cancel..." -ForegroundColor Yellow
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "PHASE 1: Uploading Shop Page Files" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green

# Shop Files
Write-Host "[1/15] Uploading footer.blade.php..." -ForegroundColor Cyan
scp "$LOCAL_PATH\resources\views\web\layouts\footer.blade.php" "${USER}@${SERVER}:${REMOTE_PATH}/resources/views/web/layouts/footer.blade.php"

Write-Host "[2/15] Uploading index.blade.php..." -ForegroundColor Cyan
scp "$LOCAL_PATH\resources\views\web\pages\index.blade.php" "${USER}@${SERVER}:${REMOTE_PATH}/resources/views/web/pages/index.blade.php"

Write-Host "[3/15] Uploading show.blade.php..." -ForegroundColor Cyan
scp "$LOCAL_PATH\resources\views\web\pages\show.blade.php" "${USER}@${SERVER}:${REMOTE_PATH}/resources/views/web/pages/show.blade.php"

Write-Host "[4/15] Uploading showDetails.blade.php..." -ForegroundColor Cyan
scp "$LOCAL_PATH\resources\views\web\pages\showDetails.blade.php" "${USER}@${SERVER}:${REMOTE_PATH}/resources/views/web/pages/showDetails.blade.php"

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "PHASE 2: Uploading New Controllers" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green

Write-Host "[5/15] Uploading InventoryController.php..." -ForegroundColor Cyan
scp "$LOCAL_PATH\app\Http\Controllers\Dashboard\InventoryController.php" "${USER}@${SERVER}:${REMOTE_PATH}/app/Http/Controllers/Dashboard/InventoryController.php"

Write-Host "[6/15] Uploading PricingController.php..." -ForegroundColor Cyan
scp "$LOCAL_PATH\app\Http\Controllers\Dashboard\PricingController.php" "${USER}@${SERVER}:${REMOTE_PATH}/app/Http/Controllers/Dashboard/PricingController.php"

Write-Host "[7/15] Uploading ReportsController.php..." -ForegroundColor Cyan
scp "$LOCAL_PATH\app\Http\Controllers\Dashboard\ReportsController.php" "${USER}@${SERVER}:${REMOTE_PATH}/app/Http/Controllers/Dashboard/ReportsController.php"

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "PHASE 3: Creating Directory Structure" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green

Write-Host "[8/15] Creating inventory directory..." -ForegroundColor Cyan
ssh "${USER}@${SERVER}" "mkdir -p ${REMOTE_PATH}/resources/views/dashboard/pages/inventory"

Write-Host "[9/15] Creating pricing directory..." -ForegroundColor Cyan
ssh "${USER}@${SERVER}" "mkdir -p ${REMOTE_PATH}/resources/views/dashboard/pages/pricing"

Write-Host "[10/15] Creating reports directory..." -ForegroundColor Cyan
ssh "${USER}@${SERVER}" "mkdir -p ${REMOTE_PATH}/resources/views/dashboard/pages/reports"

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "PHASE 4: Uploading New Dashboard Views" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green

Write-Host "[11/15] Uploading inventory manage.blade.php..." -ForegroundColor Cyan
scp "$LOCAL_PATH\resources\views\dashboard\pages\inventory\manage.blade.php" "${USER}@${SERVER}:${REMOTE_PATH}/resources/views/dashboard/pages/inventory/manage.blade.php"

Write-Host "[12/15] Uploading pricing manage.blade.php..." -ForegroundColor Cyan
scp "$LOCAL_PATH\resources\views\dashboard\pages\pricing\manage.blade.php" "${USER}@${SERVER}:${REMOTE_PATH}/resources/views/dashboard/pages/pricing/manage.blade.php"

Write-Host "[13/15] Uploading product-performance.blade.php..." -ForegroundColor Cyan
scp "$LOCAL_PATH\resources\views\dashboard\pages\reports\product-performance.blade.php" "${USER}@${SERVER}:${REMOTE_PATH}/resources/views/dashboard/pages/reports/product-performance.blade.php"

Write-Host "[14/15] Uploading sales-dashboard.blade.php..." -ForegroundColor Cyan
scp "$LOCAL_PATH\resources\views\dashboard\pages\reports\sales-dashboard.blade.php" "${USER}@${SERVER}:${REMOTE_PATH}/resources/views/dashboard/pages/reports/sales-dashboard.blade.php"

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "PHASE 5: Uploading Modified Files" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green

Write-Host "[15/15] Uploading sidebar.blade.php..." -ForegroundColor Cyan
scp "$LOCAL_PATH\resources\views\dashboard\layouts\sidebar.blade.php" "${USER}@${SERVER}:${REMOTE_PATH}/resources/views/dashboard/layouts/sidebar.blade.php"

Write-Host "[16/15] Uploading routes/dashboard.php..." -ForegroundColor Cyan
scp "$LOCAL_PATH\routes\dashboard.php" "${USER}@${SERVER}:${REMOTE_PATH}/routes/dashboard.php"

Write-Host ""
Write-Host "Files uploaded successfully!" -ForegroundColor Green
Write-Host ""
Write-Host "========================================" -ForegroundColor Yellow
Write-Host "PHASE 6: Clearing Server Caches" -ForegroundColor Yellow
Write-Host "========================================" -ForegroundColor Yellow

ssh "${USER}@${SERVER}" "cd ${REMOTE_PATH} && php artisan config:clear && php artisan cache:clear && php artisan view:clear && php artisan route:clear && php artisan optimize"

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "  DEPLOYMENT COMPLETE!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "Please verify the following pages:" -ForegroundColor Cyan
Write-Host ""
Write-Host "Shop (Customer Side):" -ForegroundColor Yellow
Write-Host "  https://phyzioline.com/shop" -ForegroundColor White
Write-Host ""
Write-Host "Dashboard (Admin Side):" -ForegroundColor Yellow
Write-Host "  https://phyzioline.com/dashboard/login" -ForegroundColor White
Write-Host "  https://phyzioline.com/dashboard/inventory/manage" -ForegroundColor White
Write-Host "  https://phyzioline.com/dashboard/pricing/manage" -ForegroundColor White
Write-Host "  https://phyzioline.com/dashboard/reports/sales-dashboard" -ForegroundColor White
Write-Host "  https://phyzioline.com/dashboard/reports/product-performance" -ForegroundColor White
Write-Host ""
Write-Host "Login Credentials:" -ForegroundColor Yellow
Write-Host "  Email: admin@admin.com" -ForegroundColor White
Write-Host "  Password: password" -ForegroundColor White
Write-Host ""

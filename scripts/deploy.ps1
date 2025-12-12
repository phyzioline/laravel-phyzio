# Automated Deployment Script for Phyzioline
# This script uploads all updated files to your server

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Phyzioline Deployment Script" -ForegroundColor Cyan
Write-Host "  Server: 147.93.85.27" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Configuration
$SERVER = "147.93.85.27"
$USER = "phyziolinegit"
$REMOTE_PATH = "/home/phyziolinegit/htdocs/phyzioline.com"
$LOCAL_PATH = "d:\laravel\phyzioline.com"

Write-Host "IMPORTANT: You will need to enter your password multiple times" -ForegroundColor Yellow
Write-Host "Press any key to continue or Ctrl+C to cancel..." -ForegroundColor Yellow
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")

Write-Host ""
Write-Host "Uploading files to server..." -ForegroundColor Green
Write-Host ""

# File 1: Footer (Most Important!)
Write-Host "[1/6] Uploading footer.blade.php..." -ForegroundColor Cyan
scp "$LOCAL_PATH\resources\views\web\layouts\footer.blade.php" "${USER}@${SERVER}:${REMOTE_PATH}/resources/views/web/layouts/footer.blade.php"

# File 2: Homepage
Write-Host "[2/6] Uploading index.blade.php..." -ForegroundColor Cyan
scp "$LOCAL_PATH\resources\views\web\pages\index.blade.php" "${USER}@${SERVER}:${REMOTE_PATH}/resources/views/web/pages/index.blade.php"

# File 3: Shop Page
Write-Host "[3/6] Uploading show.blade.php..." -ForegroundColor Cyan
scp "$LOCAL_PATH\resources\views\web\pages\show.blade.php" "${USER}@${SERVER}:${REMOTE_PATH}/resources/views/web/pages/show.blade.php"

# File 4: Product Details
Write-Host "[4/6] Uploading showDetails.blade.php..." -ForegroundColor Cyan
scp "$LOCAL_PATH\resources\views\web\pages\showDetails.blade.php" "${USER}@${SERVER}:${REMOTE_PATH}/resources/views/web/pages/showDetails.blade.php"

# File 5: Controller
Write-Host "[5/6] Uploading HomeController.php..." -ForegroundColor Cyan
scp "$LOCAL_PATH\app\Http\Controllers\Web\HomeController.php" "${USER}@${SERVER}:${REMOTE_PATH}/app/Http/Controllers/Web/HomeController.php"

# File 6: Model
Write-Host "[6/6] Uploading Product.php..." -ForegroundColor Cyan
scp "$LOCAL_PATH\app\Models\Product.php" "${USER}@${SERVER}:${REMOTE_PATH}/app/Models/Product.php"

Write-Host ""
Write-Host "Files uploaded successfully!" -ForegroundColor Green
Write-Host ""
Write-Host "Now clearing cache on server..." -ForegroundColor Yellow

# Clear cache
ssh "${USER}@${SERVER}" "cd ${REMOTE_PATH} && php artisan config:clear && php artisan cache:clear && php artisan view:clear"

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "  DEPLOYMENT COMPLETE!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "Please visit your website to verify the changes:" -ForegroundColor Cyan
Write-Host "https://phyzioline.com" -ForegroundColor Cyan
Write-Host ""
Write-Host "What to check:" -ForegroundColor Yellow
Write-Host "  [x] Footer says 'Phyzioline' (not 'Brmja Tech')" -ForegroundColor White
Write-Host "  [x] Search bar is visible" -ForegroundColor White
Write-Host "  [x] Category dropdown appears" -ForegroundColor White
Write-Host "  [x] Prices show 'EGP'" -ForegroundColor White
Write-Host "  [x] 'Order Now' buttons appear" -ForegroundColor White
Write-Host ""

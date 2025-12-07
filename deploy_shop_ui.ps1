# Deploy Shop UI Updates
# This script uploads the modified Shop page and Footer to the server

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Shop UI & Mobile Fix Deployment" -ForegroundColor Cyan
Write-Host "  Server: 147.93.85.27" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Configuration
$SERVER = "147.93.85.27"
$USER = "phyziolinegit"
$REMOTE_PATH = "/home/phyziolinegit/htdocs/phyzioline.com"
$LOCAL_PATH = "d:\laravel\phyzioline.com"

Write-Host "IMPORTANT: You will need to enter your password" -ForegroundColor Yellow
Write-Host "Press any key to continue or Ctrl+C to cancel..." -ForegroundColor Yellow
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")

Write-Host ""
Write-Host "Uploading files to server..." -ForegroundColor Green
Write-Host ""

# Upload show.blade.php
Write-Host "[1/2] Uploading show.blade.php..." -ForegroundColor Cyan
scp "$LOCAL_PATH\resources\views\web\pages\show.blade.php" "${USER}@${SERVER}:${REMOTE_PATH}/resources/views/web/pages/show.blade.php"

# Upload footer.blade.php
Write-Host "[2/2] Uploading footer.blade.php..." -ForegroundColor Cyan
scp "$LOCAL_PATH\resources\views\web\layouts\footer.blade.php" "${USER}@${SERVER}:${REMOTE_PATH}/resources/views/web/layouts/footer.blade.php"

Write-Host ""
Write-Host "Files uploaded successfully!" -ForegroundColor Green
Write-Host ""
Write-Host "Now clearing cache on server..." -ForegroundColor Yellow

# Clear cache
ssh "${USER}@${SERVER}" "cd ${REMOTE_PATH} && php artisan view:clear"

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "  DEPLOYMENT COMPLETE!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "Please check the following on your mobile device:" -ForegroundColor Cyan
Write-Host "  [x] Shop page now shows 2 products per row" -ForegroundColor White
Write-Host "  [x] Category dropdown bar is visible on mobile" -ForegroundColor White
Write-Host "  [x] Footer link says 'Appointments' instead of 'Private Cases'" -ForegroundColor White
Write-Host ""

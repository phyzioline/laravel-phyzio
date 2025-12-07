# Deploy Dashboard Login Fix
# This script uploads the fixed dashboard login view to the server

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Dashboard Login Fix Deployment" -ForegroundColor Cyan
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
Write-Host "Uploading dashboard login fix to server..." -ForegroundColor Green
Write-Host ""

# Upload the fixed login view
Write-Host "Uploading login.blade.php..." -ForegroundColor Cyan
scp "$LOCAL_PATH\resources\views\dashboard\auth\login.blade.php" "${USER}@${SERVER}:${REMOTE_PATH}/resources/views/dashboard/auth/login.blade.php"

Write-Host ""
Write-Host "File uploaded successfully!" -ForegroundColor Green
Write-Host ""
Write-Host "Now clearing cache on server..." -ForegroundColor Yellow

# Clear cache
ssh "${USER}@${SERVER}" "cd ${REMOTE_PATH} && php artisan config:clear && php artisan cache:clear && php artisan view:clear"

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "  DEPLOYMENT COMPLETE!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "Please visit the dashboard to verify the changes:" -ForegroundColor Cyan
Write-Host "https://phyzioline.com/en/dashboard/login" -ForegroundColor Cyan
Write-Host ""
Write-Host "Changes made:" -ForegroundColor Yellow
Write-Host "  [x] Changed 'Login as Supplier' to 'Dashboard Login'" -ForegroundColor White
Write-Host "  [x] Changed button text from 'Sign Up' to 'Login'" -ForegroundColor White
Write-Host ""

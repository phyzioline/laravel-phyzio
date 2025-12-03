# Quick Deploy - Footer Only
# Updates only the footer branding
# Fastest and safest deployment option

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Quick Deploy - Footer Only" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

$SERVER = "147.93.85.27"
$USER = "phyziolinegit"
$REMOTE_PATH = "/home/phyziolinegit/htdocs/phyzioline.com"
$LOCAL_PATH = "d:\laravel\phyzioline.com"

Write-Host "This will ONLY update the footer (Brmja Tech -> Phyzioline)" -ForegroundColor Yellow
Write-Host "You will need to enter your password 2 times" -ForegroundColor Yellow
Write-Host ""
Write-Host "Press any key to continue..." -ForegroundColor Green
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")

Write-Host ""
Write-Host "Uploading footer.blade.php..." -ForegroundColor Cyan
scp "$LOCAL_PATH\resources\views\web\layouts\footer.blade.php" "${USER}@${SERVER}:${REMOTE_PATH}/resources/views/web/layouts/footer.blade.php"

if ($LASTEXITCODE -eq 0) {
    Write-Host ""
    Write-Host "Clearing cache..." -ForegroundColor Yellow
    ssh "${USER}@${SERVER}" "cd ${REMOTE_PATH} && php artisan view:clear"
    
    Write-Host ""
    Write-Host "========================================" -ForegroundColor Green
    Write-Host "  FOOTER UPDATED SUCCESSFULLY!" -ForegroundColor Green
    Write-Host "========================================" -ForegroundColor Green
    Write-Host ""
    Write-Host "Visit your website and check the footer:" -ForegroundColor Cyan
    Write-Host "https://phyzioline.com" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "It should now say:" -ForegroundColor Green
    Write-Host "'Designed and developed by Phyzioline . 2025'" -ForegroundColor White
    Write-Host ""
} else {
    Write-Host ""
    Write-Host "Upload failed! Check your connection and try again." -ForegroundColor Red
    Write-Host ""
}

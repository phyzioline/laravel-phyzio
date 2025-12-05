# Nuclear Option - Clear EVERYTHING on server
Write-Host "=== NUCLEAR DEPLOYMENT OPTION ==="
Write-Host "This will:"
Write-Host "1. Verify file ownership"
Write-Host "2. Touch bootstrap/app.php to force reload"
Write-Host "3. Clear ALL Laravel caches"
Write-Host "4. List all registered routes to confirm"
Write-Host ""

ssh phyziolinegit@147.93.85.27 @"
cd /home/phyziolinegit/htdocs/phyzioline.com
echo '=== 1. Checking file ownership ==='
ls -la bootstrap/app.php
echo ''
echo '=== 2. Touching file to force reload ==='
touch bootstrap/app.php
echo ''
echo '=== 3. Clearing ALL caches ==='
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
echo ''
echo '=== 4. Checking if dashboard routes exist ==='
php artisan route:list | grep 'dashboard/inventory'
"@

Write-Host "---------------------------------------------------"
Write-Host "DEPLOYMENT COMPLETE!"
Write-Host ""
Write-Host "After this, go to CloudPanel and:"
Write-Host "1. Click 'Varnish' tab"
Write-Host "2. Click 'Purge Cache'"
Write-Host "3. Test: https://phyzioline.com/en/dashboard/inventory/manage"
Write-Host "4. Hard refresh browser (Ctrl+Shift+R)"

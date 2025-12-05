# Deploy the critical fix for 404 errors
Write-Host "Uploading fixed bootstrap/app.php..."
scp "d:\laravel\phyzioline.com\bootstrap\app.php" "phyziolinegit@147.93.85.27:/home/phyziolinegit/htdocs/phyzioline.com/bootstrap/app.php"

# Clear all caches on the server
Write-Host "Clearing server cache..."
ssh phyziolinegit@147.93.85.27 "cd /home/phyziolinegit/htdocs/phyzioline.com && php artisan route:clear && php artisan optimize && php artisan view:clear"

Write-Host "---------------------------------------------------"
Write-Host "DEPLOYMENT FINISHED!"
Write-Host "Please check: https://phyzioline.com/en/dashboard/inventory/manage"
Write-Host "It should redirect to Login (or show dashboard if logged in)."

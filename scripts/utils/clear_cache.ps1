# Clear all Laravel caches on the server
Write-Host "Clearing server cache..."

ssh phyziolinegit@147.93.85.27 "cd /home/phyziolinegit/htdocs/phyzioline.com && php artisan config:clear && php artisan cache:clear && php artisan route:clear && php artisan view:clear && php artisan optimize"

Write-Host "---------------------------------------------------"
Write-Host "CACHE CLEARED!"
Write-Host "Now test: https://phyzioline.com/en/dashboard/inventory/manage"

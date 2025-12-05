# Restart PHP-FPM to clear PHP's internal cache
Write-Host "Restarting PHP-FPM service..."

ssh phyziolinegit@147.93.85.27 "sudo systemctl restart php8.3-fpm"

Write-Host "---------------------------------------------------"
Write-Host "PHP-FPM restarted!"
Write-Host "Now test: https://phyzioline.com/en/dashboard/inventory/manage"
Write-Host "Clear your browser cache (Ctrl+Shift+R) before testing!"

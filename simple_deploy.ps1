# Simple deployment - no line ending issues
Write-Host "Running final deployment commands..."

# Run each command separately to avoid line ending issues
ssh phyziolinegit@147.93.85.27 "cd /home/phyziolinegit/htdocs/phyzioline.com && ls -la bootstrap/app.php"
ssh phyziolinegit@147.93.85.27 "cd /home/phyziolinegit/htdocs/phyzioline.com && touch bootstrap/app.php"
ssh phyziolinegit@147.93.85.27 "cd /home/phyziolinegit/htdocs/phyzioline.com && php artisan optimize:clear"
ssh phyziolinegit@147.93.85.27 "cd /home/phyziolinegit/htdocs/phyzioline.com && php artisan route:list | grep inventory"

Write-Host "---------------------------------------------------"
Write-Host "DONE! Now:"
Write-Host "1. Go to CloudPanel > Varnish > Purge Cache"
Write-Host "2. Test site with Ctrl+Shift+R"

# Deploy Updates (Therapist Features + Shop Fix + Dashboard Button)
Write-Host "Deploying updates..."

# Define files to upload
$files = @(
    "app\Http\Controllers\Web\ShowController.php",
    "resources\views\web\pages\show.blade.php",
    "resources\views\web\auth\complecet_info.blade.php",
    "resources\views\web\layouts\header.blade.php",
    "app\Http\Requests\Web\ComplecetInfoRequest.php",
    "app\Services\Web\LoginService.php"
)

# Upload each file
foreach ($file in $files) {
    $localPath = "d:\laravel\phyzioline.com\$file"
    $remotePath = "/home/phyziolinegit/htdocs/phyzioline.com/" + $file.Replace("\", "/")
    
    Write-Host "Uploading $file..."
    scp $localPath "phyziolinegit@147.93.85.27:$remotePath"
}

# Clear Cache
Write-Host "Clearing server cache..."
ssh phyziolinegit@147.93.85.27 "cd /home/phyziolinegit/htdocs/phyzioline.com && php artisan view:clear && php artisan config:clear && php artisan route:clear"

Write-Host "---------------------------------------------------"
Write-Host "DEPLOYMENT COMPLETE! 🚀"
Write-Host "1. Go to CloudPanel > Varnish > Purge Cache"
Write-Host "2. Test Therapist Signup"
Write-Host "3. Test Dashboard Button"
Write-Host "4. Check Shop Page Layout"

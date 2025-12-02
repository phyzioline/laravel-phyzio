$env:PATH = "d:\laravel\bin\php;d:\laravel\bin\node;d:\laravel\bin;$env:PATH"
Write-Host "Installing PHP dependencies..."
php d:\laravel\bin\composer.phar install
Write-Host "Installing Node dependencies..."
npm install
Write-Host "Running Migrations..."
if (!(Test-Path "database/database.sqlite")) {
    New-Item -ItemType File -Path "database/database.sqlite"
}
php artisan migrate --force
Write-Host "Setup Complete!"

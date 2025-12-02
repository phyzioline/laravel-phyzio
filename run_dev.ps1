$env:PATH = "d:\laravel\bin\php;d:\laravel\bin\node;d:\laravel\bin;$env:PATH"
Write-Host "Starting Laravel Development Server..."
Start-Process powershell -ArgumentList "-NoExit", "-Command", "$env:PATH = 'd:\laravel\bin\php;d:\laravel\bin\node;d:\laravel\bin;' + $env:PATH; cd 'd:\laravel\phyzioline.com'; php artisan serve"

Write-Host "Starting Vite Development Server..."
Start-Process powershell -ArgumentList "-NoExit", "-Command", "$env:PATH = 'd:\laravel\bin\php;d:\laravel\bin\node;d:\laravel\bin;' + $env:PATH; cd 'd:\laravel\phyzioline.com'; npm run dev"

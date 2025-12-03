@echo off
echo ========================================
echo  Quick Deploy - Footer Only
echo ========================================
echo.

cd /d D:\laravel\phyzioline.com

echo Uploading footer.blade.php...
scp resources\views\web\layouts\footer.blade.php phyziolinegit@147.93.85.27:/home/phyziolinegit/htdocs/phyzioline.com/resources/views/web/layouts/footer.blade.php

echo.
echo ========================================
echo  Footer uploaded!
echo  Now go to your SSH window and type:
echo  php artisan view:clear
echo ========================================
echo.
echo Then visit: https://phyzioline.com
echo Check if footer says "Phyzioline"
echo.
pause

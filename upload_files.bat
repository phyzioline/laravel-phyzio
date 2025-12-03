@echo off
echo ========================================
echo  Phyzioline Deployment
echo  Uploading files to server...
echo ========================================
echo.

cd /d D:\laravel\phyzioline.com

echo [1/6] Uploading footer...
scp resources\views\web\layouts\footer.blade.php phyziolinegit@147.93.85.27:/home/phyziolinegit/htdocs/phyzioline.com/resources/views/web/layouts/footer.blade.php

echo [2/6] Uploading homepage...
scp resources\views\web\pages\index.blade.php phyziolinegit@147.93.85.27:/home/phyziolinegit/htdocs/phyzioline.com/resources/views/web/pages/index.blade.php

echo [3/6] Uploading shop page...
scp resources\views\web\pages\show.blade.php phyziolinegit@147.93.85.27:/home/phyziolinegit/htdocs/phyzioline.com/resources/views/web/pages/show.blade.php

echo [4/6] Uploading details page...
scp resources\views\web\pages\showDetails.blade.php phyziolinegit@147.93.85.27:/home/phyziolinegit/htdocs/phyzioline.com/resources/views/web/pages/showDetails.blade.php

echo [5/6] Uploading controller...
scp app\Http\Controllers\Web\HomeController.php phyziolinegit@147.93.85.27:/home/phyziolinegit/htdocs/phyzioline.com/app/Http/Controllers/Web/HomeController.php

echo [6/6] Uploading model...
scp app\Models\Product.php phyziolinegit@147.93.85.27:/home/phyziolinegit/htdocs/phyzioline.com/app/Models/Product.php

echo.
echo ========================================
echo  Upload complete!
echo  Now go to your SSH window and type:
echo  php artisan view:clear
echo ========================================
pause

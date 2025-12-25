#!/bin/bash

# Complete Company Recruitment System Deployment
# Run this on your server after git pull

echo "=========================================="
echo "🚀 Company Recruitment System Deployment"
echo "=========================================="
echo ""

cd /home/phyziolinegit/htdocs/phyzioline.com

echo "✅ Step 1: Clearing all caches..."
php artisan view:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear
echo "✅ Caches cleared"
echo ""

echo "✅ Step 2: Fixing permissions..."
chmod -R 775 storage bootstrap/cache
echo "✅ Permissions fixed"
echo ""

echo "✅ Step 3: Rebuilding caches..."
php artisan view:cache
php artisan config:cache
php artisan route:cache
echo "✅ Caches rebuilt"
echo ""

echo "=========================================="
echo "✅ Deployment Complete!"
echo "=========================================="
echo ""
echo "Company recruitment system is now live!"
echo "Companies can now:"
echo "  - Access their dashboard at /company/dashboard"
echo "  - Post and manage jobs"
echo "  - View and manage applications"
echo ""


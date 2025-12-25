#!/bin/bash

# Fix Company Registration Error - Server Permissions Script
# Run this on your server via SSH

echo "=========================================="
echo "🔧 Fixing Company Registration Error"
echo "=========================================="
echo ""

# Navigate to project
cd /home/phyziolinegit/htdocs/phyzioline.com || {
    echo "❌ Error: Cannot access project directory"
    exit 1
}

echo "✅ Step 1: Clearing all Laravel caches..."
php artisan view:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear
echo "✅ Caches cleared"
echo ""

echo "✅ Step 2: Removing compiled views..."
rm -rf storage/framework/views/*
echo "✅ Compiled views removed"
echo ""

echo "✅ Step 3: Fixing storage permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache
echo "✅ Permissions fixed"
echo ""

echo "✅ Step 4: Rebuilding caches..."
php artisan view:cache
php artisan config:cache
php artisan route:cache
echo "✅ Caches rebuilt"
echo ""

echo "=========================================="
echo "✅ Fix Complete!"
echo "=========================================="
echo ""
echo "Now try accessing:"
echo "  - https://phyzioline.com/en/register/company"
echo "  - https://phyzioline.com/ar/register/company"
echo ""


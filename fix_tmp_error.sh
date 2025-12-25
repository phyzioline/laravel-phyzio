#!/bin/bash

# Fix /tmp File Error for Company Registration
# Run this on your server via SSH

echo "=========================================="
echo "🔧 Fixing /tmp File Error"
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

echo "✅ Step 2: Removing ALL compiled views..."
rm -rf storage/framework/views/*
echo "✅ Compiled views removed"
echo ""

echo "✅ Step 3: Creating alternative temp directory..."
mkdir -p storage/framework/tmp
chmod 775 storage/framework/tmp
chown phyziolinegit:phyziolinegit storage/framework/tmp 2>/dev/null || echo "⚠️  Could not change ownership (may need sudo)"
echo "✅ Temp directory created"
echo ""

echo "✅ Step 4: Fixing storage permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chown -R phyziolinegit:phyziolinegit storage bootstrap/cache 2>/dev/null || echo "⚠️  Could not change ownership (may need sudo)"
echo "✅ Permissions fixed"
echo ""

echo "✅ Step 5: Rebuilding caches..."
php artisan view:cache
php artisan config:cache
php artisan route:cache
echo "✅ Caches rebuilt"
echo ""

echo "=========================================="
echo "✅ Fix Complete!"
echo "=========================================="
echo ""
echo "Now test:"
echo "  - https://phyzioline.com/en/register/company"
echo "  - https://phyzioline.com/ar/register/company"
echo ""
echo "If error persists, check PHP temp directory:"
echo "  php -i | grep sys_temp_dir"
echo ""


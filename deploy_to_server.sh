#!/bin/bash

echo "=========================================="
echo "🚀 Phyzioline Database Restoration"
echo "=========================================="
echo ""

# Step 1: Pull latest code from Git
echo "📥 Step 1: Pulling latest code from Git..."
echo "------------------------------------------"
git pull origin main

if [ $? -eq 0 ]; then
    echo "✅ Code updated successfully"
else
    echo "❌ Git pull failed"
    exit 1
fi

echo ""

# Step 2: Check database file
echo "💾 Step 2: Checking database file..."
echo "------------------------------------------"

if [ -f "database/database.sqlite" ]; then
    DB_SIZE=$(du -h database/database.sqlite | cut -f1)
    echo "✅ Database file found"
    echo "   Size: $DB_SIZE"
    
    # Set correct permissions
    chmod 664 database/database.sqlite
    echo "✅ Permissions set (664)"
else
    echo "❌ Database file not found!"
    echo "   Please upload database/database.sqlite manually"
    exit 1
fi

echo ""

# Step 3: Install/Update dependencies
echo "📦 Step 3: Installing dependencies..."
echo "------------------------------------------"
composer install --no-dev --optimize-autoloader

if [ $? -eq 0 ]; then
    echo "✅ Dependencies installed"
else
    echo "⚠️  Warning: Composer install had issues"
fi

echo ""

# Step 4: Clear all caches
echo "🧹 Step 4: Clearing Laravel cache..."
echo "------------------------------------------"
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
echo "✅ Cache cleared"

echo ""

# Step 5: Optimize for production
echo "⚡ Step 5: Optimizing for production..."
echo "------------------------------------------"
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "✅ Optimizations applied"

echo ""

# Step 6: Set correct permissions
echo "🔐 Step 6: Setting permissions..."
echo "------------------------------------------"
chmod -R 755 storage bootstrap/cache
echo "✅ Permissions set"

echo ""

# Step 7: Verify restoration
echo "🔍 Step 7: Verifying restoration..."
echo "------------------------------------------"

if [ -f "verify_restoration.php" ]; then
    php verify_restoration.php
else
    echo "Running quick verification..."
    php artisan tinker --execute="
        \$vendor = \App\Models\User::where('email', 'phyzioline@gmail.com')->first();
        if (\$vendor) {
            echo '✅ Vendor found: ' . \$vendor->name . '\n';
            \$products = \App\Models\Product::where('user_id', \$vendor->id)->count();
            echo '✅ Products: ' . \$products . '\n';
            \$images = \App\Models\ProductImage::count();
            echo '✅ Product Images: ' . \$images . '\n';
        } else {
            echo '❌ Vendor not found\n';
        }
    "
fi

echo ""
echo "=========================================="
echo "✅ Deployment Complete!"
echo "=========================================="
echo ""
echo "Next steps:"
echo "1. Visit https://phyzioline.com to test"
echo "2. Login as vendor: phyzioline@gmail.com"
echo "3. Check products are visible"
echo "4. Verify images load correctly"
echo ""

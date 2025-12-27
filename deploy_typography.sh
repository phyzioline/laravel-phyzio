#!/bin/bash
# Typography & Logo Update Deployment Script
# Run this on your server after git pull

echo "=========================================="
echo "🚀 Phyzioline Typography & Logo Update"
echo "=========================================="
echo ""

# Navigate to project directory
cd /home/phyziolinegit/htdocs/phyzioline.com

# 1. Pull the latest changes
echo "📥 Step 1: Pulling latest changes from GitHub..."
echo "------------------------------------------"
git pull origin main

if [ $? -eq 0 ]; then
    echo "✅ Code updated successfully"
else
    echo "❌ Git pull failed"
    exit 1
fi

echo ""

# 2. Clear all caches (IMPORTANT - we changed Blade templates)
echo "🧹 Step 2: Clearing Laravel cache..."
echo "------------------------------------------"
php artisan view:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear
echo "✅ Cache cleared"

echo ""

# 3. Rebuild caches for production
echo "⚡ Step 3: Rebuilding caches for production..."
echo "------------------------------------------"
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "✅ Caches rebuilt"

echo ""
echo "=========================================="
echo "✅ Typography update completed successfully!"
echo "=========================================="
echo ""
echo "Changes deployed:"
echo "  • Inter font typography system (weights: 400, 500, 600, 700)"
echo "  • Phyzioline logo in all dashboard sidebars"
echo "  • Professional typography hierarchy"
echo "  • Consistent branding across all dashboards"
echo ""
echo "⚠️  Note: No database migration required"
echo ""


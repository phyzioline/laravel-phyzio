#!/bin/bash
# Server Update Commands - UI Improvements & Fixes
# Run these commands on your server after connecting via SSH

echo "=========================================="
echo "🚀 Phyzioline Server Update"
echo "=========================================="
echo ""

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

# 2. Run migration (if any new migrations exist)
echo "💾 Step 2: Running database migrations..."
echo "------------------------------------------"
php artisan migrate --force

if [ $? -eq 0 ]; then
    echo "✅ Migrations completed"
else
    echo "⚠️  Warning: Migration had issues (may be normal if no new migrations)"
fi

echo ""

# 3. Clear all caches
echo "🧹 Step 3: Clearing Laravel cache..."
echo "------------------------------------------"
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
echo "✅ Cache cleared"

echo ""

# 4. Rebuild caches for production
echo "⚡ Step 4: Rebuilding caches for production..."
echo "------------------------------------------"
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "✅ Caches rebuilt"

echo ""
echo "=========================================="
echo "✅ Server update completed successfully!"
echo "=========================================="
echo ""
echo "Changes deployed:"
echo "  • Selected text color fix on home page"
echo "  • Payment method dropdown styling improvements"
echo "  • Payment method validation"
echo "  • Cash order route fix"
echo ""


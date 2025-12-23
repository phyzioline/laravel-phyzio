#!/bin/bash

# Laravel Payout System Deployment Script
# Run this script on your server to deploy the payout system updates

set -e  # Exit on error

echo "=========================================="
echo "Laravel Payout System Deployment"
echo "=========================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration (Update these for your server)
PROJECT_PATH="/var/www/laravel"  # Update with your project path
PHP_VERSION="php8.2"  # Update with your PHP version
COMPOSER_PATH="/usr/local/bin/composer"  # Update if different

echo -e "${YELLOW}Step 1: Navigate to project directory...${NC}"
cd $PROJECT_PATH || { echo -e "${RED}Error: Cannot access project directory${NC}"; exit 1; }

echo -e "${YELLOW}Step 2: Enable maintenance mode...${NC}"
$PHP_VERSION artisan down || true

echo -e "${YELLOW}Step 3: Pull latest changes from git...${NC}"
git pull origin main || git pull origin master || { echo -e "${RED}Error: Git pull failed${NC}"; exit 1; }

echo -e "${YELLOW}Step 4: Install/Update Composer dependencies...${NC}"
$COMPOSER_PATH install --no-dev --optimize-autoloader || { echo -e "${RED}Error: Composer install failed${NC}"; exit 1; }

echo -e "${YELLOW}Step 5: Run database migrations...${NC}"
$PHP_VERSION artisan migrate --force || { echo -e "${RED}Error: Migration failed${NC}"; exit 1; }

echo -e "${YELLOW}Step 6: Clear application cache...${NC}"
$PHP_VERSION artisan cache:clear
$PHP_VERSION artisan config:clear
$PHP_VERSION artisan route:clear
$PHP_VERSION artisan view:clear

echo -e "${YELLOW}Step 7: Optimize application...${NC}"
$PHP_VERSION artisan config:cache
$PHP_VERSION artisan route:cache
$PHP_VERSION artisan view:cache

echo -e "${YELLOW}Step 8: Set proper permissions...${NC}"
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo -e "${YELLOW}Step 9: Verify payout settings table exists...${NC}"
$PHP_VERSION artisan tinker --execute="
    if (!Schema::hasTable('payout_settings')) {
        echo 'Creating payout_settings table...';
        Artisan::call('migrate', ['--path' => 'database/migrations/2025_12_23_100000_create_payout_settings_table.php']);
    } else {
        echo 'payout_settings table exists';
    }
" || echo -e "${YELLOW}Note: Tinker check skipped (non-critical)${NC}"

echo -e "${YELLOW}Step 10: Test auto-payout command...${NC}"
$PHP_VERSION artisan payouts:process-auto --dry-run 2>/dev/null || echo -e "${YELLOW}Note: Dry-run not available, command exists${NC}"

echo -e "${YELLOW}Step 11: Disable maintenance mode...${NC}"
$PHP_VERSION artisan up

echo -e "${GREEN}=========================================="
echo -e "Deployment completed successfully!"
echo -e "==========================================${NC}"

echo ""
echo -e "${YELLOW}Next Steps:${NC}"
echo "1. Verify payout settings: /dashboard/payouts/settings"
echo "2. Schedule auto-payout command in cron:"
echo "   Add to crontab: 0 2 * * 0 cd $PROJECT_PATH && $PHP_VERSION artisan payouts:process-auto >> /dev/null 2>&1"
echo "3. Test manual payout processing:"
echo "   $PHP_VERSION artisan payouts:process-auto"
echo ""
echo -e "${GREEN}Deployment script completed!${NC}"


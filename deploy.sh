#!/bin/bash
# Deployment Script for Phyzioline Server
# Run these commands after connecting via SSH

# Navigate to project directory
cd /home/phyziolinegit/htdocs/phyzioline.com

# Pull latest changes from GitHub
git pull origin main

# Run database migrations
php artisan migrate --force

# Clear all caches
php artisan config:clear && php artisan cache:clear && php artisan view:clear

echo "Deployment completed successfully!"


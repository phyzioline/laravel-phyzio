#!/bin/bash

# SSH Deployment Script for Phyzioline.com
# This script deploys the latest changes to production server

echo "=================================="
echo "Phyzioline.com Deployment Script"
echo "=================================="
echo ""

# SSH connection details - UPDATE THESE WITH YOUR CREDENTIALS
SSH_USER="your_username"
SSH_HOST="phyzioline.com"
SSH_PORT="22"
PROJECT_PATH="/home/username/public_html"  # Update this path

echo "Connecting to server: $SSH_USER@$SSH_HOST"
echo ""

# Connect to server and execute deployment commands
ssh -p $SSH_PORT $SSH_USER@$SSH_HOST << 'ENDSSH'
    echo "Connected to production server!"
    echo ""
    
    # Navigate to project directory
    cd /home/username/public_html  # UPDATE THIS PATH
    
    echo "Current directory: $(pwd)"
    echo ""
    
    echo "Step 1: Pulling latest code from Git..."
    git pull origin main
    echo ""
    
    echo "Step 2: Clearing configuration cache..."
    php artisan config:clear
    echo ""
    
    echo "Step 3: Clearing route cache..."
    php artisan route:clear
    echo ""
    
    echo "Step 4: Clearing view cache..."
    php artisan view:clear
    echo ""
    
    echo "Step 5: Clearing application cache..."
    php artisan cache:clear
    echo ""
    
    echo "Step 6: Rebuilding route cache..."
    php artisan route:cache
    echo ""
    
    echo "Step 7: Rebuilding config cache..."
    php artisan config:cache
    echo ""
    
    echo "✅ Deployment completed successfully!"
    echo ""
    echo "Test these URLs:"
    echo "  - https://phyzioline.com/en/dashboard/home"
    echo "  - https://phyzioline.com/en/dashboard/inventory/manage"
    echo "  - https://phyzioline.com/en/dashboard/reports/sales-dashboard"
    echo ""
ENDSSH

echo ""
echo "Deployment finished!"
echo "=================================="

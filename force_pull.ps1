# Force Pull Script - WIPES SERVER CHANGES and pulls fresh from Git
Write-Host "Connecting to server to FORCE pull changes..."

# Command explanation:
# 1. git fetch --all: Get latest info from GitHub
# 2. git reset --hard origin/main: FORCE server files to match GitHub exactly (wipes local changes)
# 3. git clean -fd: Remove any untracked files (like the files we uploaded manually)
# 4. php artisan ...: Clear caches

ssh phyziolinegit@147.93.85.27 "cd /home/phyziolinegit/htdocs/phyzioline.com && git fetch --all && git reset --hard origin/main && git clean -fd && php artisan config:clear && php artisan cache:clear && php artisan route:clear && php artisan view:clear"

Write-Host "---------------------------------------------------"
Write-Host "FORCE PULL COMPLETE!"
Write-Host "The server now exactly matches your GitHub repository."
Write-Host "Please check: https://phyzioline.com/en/dashboard/inventory/manage"

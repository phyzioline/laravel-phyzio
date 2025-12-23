# Laravel Payout System Deployment Script (PowerShell)
# Run this script on Windows Server or locally before pushing

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "Laravel Payout System - Pre-Deployment Check" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan

# Check if we're in Laravel directory
if (-not (Test-Path "artisan")) {
    Write-Host "Error: Not in Laravel project directory!" -ForegroundColor Red
    exit 1
}

Write-Host "`nStep 1: Checking Git status..." -ForegroundColor Yellow
git status

Write-Host "`nStep 2: Running PHP syntax check..." -ForegroundColor Yellow
Get-ChildItem -Path "app" -Recurse -Filter "*.php" | ForEach-Object {
    $result = php -l $_.FullName 2>&1
    if ($LASTEXITCODE -ne 0) {
        Write-Host "Syntax error in: $($_.FullName)" -ForegroundColor Red
        Write-Host $result -ForegroundColor Red
    }
}

Write-Host "`nStep 3: Checking for migration file..." -ForegroundColor Yellow
if (Test-Path "database/migrations/2025_12_23_100000_create_payout_settings_table.php") {
    Write-Host "✓ Migration file exists" -ForegroundColor Green
} else {
    Write-Host "✗ Migration file missing!" -ForegroundColor Red
}

Write-Host "`nStep 4: Verifying new files exist..." -ForegroundColor Yellow
$requiredFiles = @(
    "app/Models/PayoutSetting.php",
    "app/Console/Commands/ProcessAutoPayouts.php",
    "app/Http/Controllers/Dashboard/PayoutSettingController.php",
    "resources/views/dashboard/pages/payouts/settings.blade.php",
    "resources/views/dashboard/pages/finance/all-statements.blade.php",
    "resources/views/dashboard/pages/finance/disbursements.blade.php",
    "resources/views/dashboard/pages/finance/advertising.blade.php",
    "resources/views/dashboard/pages/finance/reports.blade.php"
)

foreach ($file in $requiredFiles) {
    if (Test-Path $file) {
        Write-Host "✓ $file" -ForegroundColor Green
    } else {
        Write-Host "✗ Missing: $file" -ForegroundColor Red
    }
}

Write-Host "`nStep 5: Checking modified files..." -ForegroundColor Yellow
$modifiedFiles = @(
    "app/Services/WalletService.php",
    "app/Services/PayoutService.php",
    "app/Http/Controllers/Dashboard/PaymentController.php",
    "routes/dashboard.php",
    "resources/views/dashboard/pages/finance/layout.blade.php",
    "resources/views/dashboard/layouts/sidebar.blade.php"
)

foreach ($file in $modifiedFiles) {
    if (Test-Path $file) {
        Write-Host "✓ $file" -ForegroundColor Green
    } else {
        Write-Host "✗ Missing: $file" -ForegroundColor Red
    }
}

Write-Host "`n==========================================" -ForegroundColor Cyan
Write-Host "Pre-deployment check completed!" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "`nReady to commit and push to git." -ForegroundColor Green


Write-Host "===========================================================" -ForegroundColor Cyan
Write-Host "DATA RESTORATION: laravel1 to laravel" -ForegroundColor Cyan
Write-Host "===========================================================" -ForegroundColor Cyan
Write-Host ""

$source = "d:\laravel1"
$target = "d:\laravel"
$errors = @()
$warnings = @()
$success = @()

# Check if source exists
if (-not (Test-Path $source)) {
    Write-Host "[ERROR] Source directory not found: $source" -ForegroundColor Red
    exit 1
}

if (-not (Test-Path $target)) {
    Write-Host "[ERROR] Target directory not found: $target" -ForegroundColor Red
    exit 1
}

Write-Host "[OK] Source: $source" -ForegroundColor Green
Write-Host "[OK] Target: $target" -ForegroundColor Green
Write-Host ""

# Step 1: Backup current data
Write-Host "-----------------------------------------------------------" -ForegroundColor Gray
Write-Host "Step 1: Creating backup of current data..." -ForegroundColor Yellow
Write-Host "-----------------------------------------------------------" -ForegroundColor Gray

$backupDir = "d:\laravel_backup_$(Get-Date -Format 'yyyyMMdd_HHmmss')"
try {
    New-Item -ItemType Directory -Path $backupDir -Force | Out-Null
    Write-Host "[OK] Backup directory created: $backupDir" -ForegroundColor Green
    $success += "Backup directory created"
    
    # Backup database if exists
    if (Test-Path "$target\database\database.sqlite") {
        Copy-Item "$target\database\database.sqlite" "$backupDir\database.sqlite" -Force
        Write-Host "[OK] Current database backed up" -ForegroundColor Green
        $success += "Database backed up"
    }
    
    # Backup images if exist
    if (Test-Path "$target\public\uploads\products") {
        $currentImages = Get-ChildItem "$target\public\uploads\products" -File -ErrorAction SilentlyContinue
        if ($currentImages.Count -gt 0) {
            New-Item -ItemType Directory -Path "$backupDir\images" -Force | Out-Null
            Copy-Item "$target\public\uploads\products\*" "$backupDir\images\" -Recurse -Force
            Write-Host "[OK] Current images backed up: $($currentImages.Count) files" -ForegroundColor Green
            $success += "Images backed up"
        }
    }
} catch {
    Write-Host "[WARN] Backup failed: $_" -ForegroundColor Yellow
    $warnings += "Backup failed: $_"
}
Write-Host ""

# Step 2: Copy Product Images
Write-Host "-----------------------------------------------------------" -ForegroundColor Gray
Write-Host "Step 2: Copying product images..." -ForegroundColor Yellow
Write-Host "-----------------------------------------------------------" -ForegroundColor Gray

try {
    # Ensure target directory exists
    $targetImagesDir = "$target\public\uploads\products"
    New-Item -ItemType Directory -Path $targetImagesDir -Force | Out-Null
    
    # Check source images
    $sourceImagesDir = "$source\public\uploads\products"
    if (Test-Path $sourceImagesDir) {
        $sourceImages = Get-ChildItem $sourceImagesDir -File
        Write-Host "Source images found: $($sourceImages.Count) files" -ForegroundColor Cyan
        
        # Copy all images
        Copy-Item "$sourceImagesDir\*" $targetImagesDir -Force
        
        # Verify copy
        $targetImages = Get-ChildItem $targetImagesDir -File
        Write-Host "Target images after copy: $($targetImages.Count) files" -ForegroundColor Cyan
        
        if ($sourceImages.Count -eq $targetImages.Count) {
            Write-Host "[OK] All images copied successfully!" -ForegroundColor Green
            $success += "Copied $($targetImages.Count) product images"
            
            # Show breakdown
            $jpg = ($targetImages | Where-Object {$_.Extension -eq '.jpg'}).Count
            $png = ($targetImages | Where-Object {$_.Extension -eq '.png'}).Count
            $jpeg = ($targetImages | Where-Object {$_.Extension -eq '.jpeg'}).Count
            Write-Host "   - JPG: $jpg" -ForegroundColor Gray
            Write-Host "   - PNG: $png" -ForegroundColor Gray
            Write-Host "   - JPEG: $jpeg" -ForegroundColor Gray
        } else {
            Write-Host "[WARN] Image count mismatch!" -ForegroundColor Yellow
            Write-Host "   Source: $($sourceImages.Count), Target: $($targetImages.Count)" -ForegroundColor Yellow
            $warnings += "Image count mismatch: Source=$($sourceImages.Count), Target=$($targetImages.Count)"
        }
    } else {
        Write-Host "[ERROR] Source images directory not found: $sourceImagesDir" -ForegroundColor Red
        $errors += "Source images directory not found"
    }
} catch {
    Write-Host "[ERROR] Error copying images: $_" -ForegroundColor Red
    $errors += "Image copy failed: $_"
}
Write-Host ""

# Step 3: Copy Database
Write-Host "-----------------------------------------------------------" -ForegroundColor Gray
Write-Host "Step 3: Copying database..." -ForegroundColor Yellow
Write-Host "-----------------------------------------------------------" -ForegroundColor Gray

try {
    # Check for SQLite database
    $sourceSqlite = "$source\database\database.sqlite"
    if (Test-Path $sourceSqlite) {
        $dbSize = (Get-Item $sourceSqlite).Length / 1MB
        Write-Host "SQLite database found: $([math]::Round($dbSize, 2)) MB" -ForegroundColor Cyan
        
        # Ensure target database directory exists
        New-Item -ItemType Directory -Path "$target\database" -Force | Out-Null
        
        # Copy database
        Copy-Item $sourceSqlite "$target\database\database.sqlite" -Force
        
        # Verify copy
        if (Test-Path "$target\database\database.sqlite") {
            $targetDbSize = (Get-Item "$target\database\database.sqlite").Length / 1MB
            Write-Host "[OK] SQLite database copied: $([math]::Round($targetDbSize, 2)) MB" -ForegroundColor Green
            $success += "SQLite database copied"
        } else {
            Write-Host "[ERROR] Database copy verification failed" -ForegroundColor Red
            $errors += "Database copy verification failed"
        }
    } else {
        Write-Host "[WARN] No SQLite database found in source" -ForegroundColor Yellow
        Write-Host "   If using MySQL, you'll need to export/import manually" -ForegroundColor Yellow
        Write-Host "   See restoration_plan.md for MySQL instructions" -ForegroundColor Yellow
        $warnings += "No SQLite database found - manual MySQL export/import required"
    }
} catch {
    Write-Host "[ERROR] Error copying database: $_" -ForegroundColor Red
    $errors += "Database copy failed: $_"
}
Write-Host ""

# Step 4: Clear Laravel Cache
Write-Host "-----------------------------------------------------------" -ForegroundColor Gray
Write-Host "Step 4: Clearing Laravel cache..." -ForegroundColor Yellow
Write-Host "-----------------------------------------------------------" -ForegroundColor Gray

try {
    Push-Location $target
    
    # Find PHP executable
    $phpPaths = @(
        "C:\xampp\php\php.exe",
        "C:\wamp64\bin\php\php8.2.12\php.exe",
        "C:\wamp\bin\php\php8.2.12\php.exe",
        "C:\laragon\bin\php\php82\php.exe",
        "C:\laragon\bin\php\php8.2\php.exe"
    )
    
    $phpExe = $null
    foreach ($path in $phpPaths) {
        if (Test-Path $path) {
            $phpExe = $path
            Write-Host "[OK] Found PHP: $phpExe" -ForegroundColor Green
            break
        }
    }
    
    if ($phpExe) {
        # Clear caches
        Write-Host "Clearing cache..." -ForegroundColor Cyan
        & $phpExe artisan cache:clear 2>&1 | Out-Null
        & $phpExe artisan config:clear 2>&1 | Out-Null
        & $phpExe artisan route:clear 2>&1 | Out-Null
        & $phpExe artisan view:clear 2>&1 | Out-Null
        Write-Host "[OK] Laravel cache cleared" -ForegroundColor Green
        $success += "Laravel cache cleared"
    } else {
        Write-Host "[WARN] PHP executable not found - skipping cache clear" -ForegroundColor Yellow
        Write-Host "   You can manually run: php artisan cache:clear" -ForegroundColor Yellow
        $warnings += "PHP not found - cache not cleared"
    }
    
    Pop-Location
} catch {
    Write-Host "[WARN] Cache clear failed: $_" -ForegroundColor Yellow
    $warnings += "Cache clear failed: $_"
    Pop-Location
}
Write-Host ""

# Summary
Write-Host "===========================================================" -ForegroundColor Cyan
Write-Host "RESTORATION SUMMARY" -ForegroundColor Cyan
Write-Host "===========================================================" -ForegroundColor Cyan
Write-Host ""

if ($success.Count -gt 0) {
    Write-Host "SUCCESSES ($($success.Count)):" -ForegroundColor Green
    foreach ($msg in $success) {
        Write-Host "   * $msg" -ForegroundColor Green
    }
    Write-Host ""
}

if ($warnings.Count -gt 0) {
    Write-Host "WARNINGS ($($warnings.Count)):" -ForegroundColor Yellow
    foreach ($msg in $warnings) {
        Write-Host "   * $msg" -ForegroundColor Yellow
    }
    Write-Host ""
}

if ($errors.Count -gt 0) {
    Write-Host "ERRORS ($($errors.Count)):" -ForegroundColor Red
    foreach ($msg in $errors) {
        Write-Host "   * $msg" -ForegroundColor Red
    }
    Write-Host ""
}

# Final verdict
Write-Host "===========================================================" -ForegroundColor Cyan
if ($errors.Count -eq 0 -and $warnings.Count -eq 0) {
    Write-Host "RESTORATION COMPLETE!" -ForegroundColor Green
    Write-Host ""
    Write-Host "Next steps:" -ForegroundColor White
    Write-Host "1. Run verification: php verify_restoration.php" -ForegroundColor Gray
    Write-Host "2. Test your website" -ForegroundColor Gray
    Write-Host "3. Check vendor dashboard" -ForegroundColor Gray
} elseif ($errors.Count -eq 0) {
    Write-Host "RESTORATION MOSTLY SUCCESSFUL" -ForegroundColor Green
    Write-Host ""
    Write-Host "Review warnings above and run verification:" -ForegroundColor White
    Write-Host "php verify_restoration.php" -ForegroundColor Gray
} else {
    Write-Host "RESTORATION INCOMPLETE" -ForegroundColor Red
    Write-Host ""
    Write-Host "Please review errors above and:" -ForegroundColor White
    Write-Host "1. Check restoration_plan.md for manual steps" -ForegroundColor Gray
    Write-Host "2. Fix errors and run this script again" -ForegroundColor Gray
}
Write-Host "===========================================================" -ForegroundColor Cyan

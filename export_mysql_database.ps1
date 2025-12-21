Write-Host "===========================================================" -ForegroundColor Cyan
Write-Host "MySQL Database Finder and Export Tool" -ForegroundColor Cyan
Write-Host "===========================================================" -ForegroundColor Cyan
Write-Host ""

# Step 1: Find MySQL installation
Write-Host "Step 1: Finding MySQL installation..." -ForegroundColor Yellow
Write-Host "-----------------------------------------------------------" -ForegroundColor Gray

$mysqlPaths = @{
    "XAMPP" = "C:\xampp\mysql\bin"
    "WAMP64" = "C:\wamp64\bin\mysql"
    "WAMP" = "C:\wamp\bin\mysql"
    "Laragon" = "C:\laragon\bin\mysql"
}

$mysqlBinPath = $null
$mysqlType = $null

foreach ($type in $mysqlPaths.Keys) {
    $basePath = $mysqlPaths[$type]
    if (Test-Path $basePath) {
        # For WAMP and Laragon, need to find version subfolder
        if ($type -like "WAMP*" -or $type -eq "Laragon") {
            $versionDirs = Get-ChildItem $basePath -Directory | Where-Object {$_.Name -like "mysql*"}
            if ($versionDirs) {
                $mysqlBinPath = Join-Path $versionDirs[0].FullName "bin"
            }
        } else {
            $mysqlBinPath = $basePath
        }
        
        if ($mysqlBinPath -and (Test-Path (Join-Path $mysqlBinPath "mysql.exe"))) {
            $mysqlType = $type
            Write-Host "[FOUND] $type MySQL at: $mysqlBinPath" -ForegroundColor Green
            break
        }
    }
}

if (-not $mysqlBinPath) {
    Write-Host "[ERROR] MySQL not found in common locations!" -ForegroundColor Red
    Write-Host ""
    Write-Host "Please install one of the following:" -ForegroundColor Yellow
    Write-Host "  - XAMPP (https://www.apachefriends.org/)" -ForegroundColor Gray
    Write-Host "  - WAMP (https://www.wampserver.com/)" -ForegroundColor Gray
    Write-Host "  - Laragon (https://laragon.org/)" -ForegroundColor Gray
    Write-Host ""
    Write-Host "Or use phpMyAdmin to export the database manually." -ForegroundColor Yellow
    exit 1
}

$mysqlExe = Join-Path $mysqlBinPath "mysql.exe"
$mysqldumpExe = Join-Path $mysqlBinPath "mysqldump.exe"

Write-Host ""

# Step 2: Get database credentials
Write-Host "Step 2: Reading database configuration..." -ForegroundColor Yellow
Write-Host "-----------------------------------------------------------" -ForegroundColor Gray

# Try to read from .env (but it's gitignored, so might not exist)
$dbHost = "127.0.0.1"
$dbPort = "3306"
$dbUser = "root"
$dbPass = ""
$dbName = $null

# Common database names to try
$commonDbNames = @(
    "phyzioline",
    "laravel",
    "phyzioline_db",
    "laravel_db"
)

Write-Host "Default credentials:" -ForegroundColor Cyan
Write-Host "  Host: $dbHost" -ForegroundColor Gray
Write-Host "  Port: $dbPort" -ForegroundColor Gray
Write-Host "  User: $dbUser" -ForegroundColor Gray
Write-Host "  Password: (empty)" -ForegroundColor Gray
Write-Host ""

# Step 3: List available databases
Write-Host "Step 3: Listing available databases..." -ForegroundColor Yellow
Write-Host "-----------------------------------------------------------" -ForegroundColor Gray

try {
    $query = "SHOW DATABASES;"
    $result = & $mysqlExe -h $dbHost -P $dbPort -u $dbUser -e $query 2>&1
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host "[SUCCESS] Connected to MySQL!" -ForegroundColor Green
        Write-Host ""
        Write-Host "Available databases:" -ForegroundColor Cyan
        $databases = $result | Select-Object -Skip 1 | Where-Object {$_ -notmatch "information_schema|mysql|performance_schema|sys"}
        $databases | ForEach-Object { Write-Host "  - $_" -ForegroundColor White }
        Write-Host ""
        
        # Try to find the most likely database
        $likelyDb = $databases | Where-Object {$_ -like "*phyzioline*" -or $_ -like "*laravel*"} | Select-Object -First 1
        if ($likelyDb) {
            $dbName = $likelyDb
            Write-Host "[SUGGESTION] Most likely database: $dbName" -ForegroundColor Green
        }
    } else {
        Write-Host "[ERROR] Could not connect to MySQL" -ForegroundColor Red
        Write-Host "Error: $result" -ForegroundColor Red
        Write-Host ""
        Write-Host "Please check:" -ForegroundColor Yellow
        Write-Host "  1. MySQL service is running" -ForegroundColor Gray
        Write-Host "  2. Credentials are correct" -ForegroundColor Gray
        Write-Host "  3. Try accessing phpMyAdmin at http://localhost/phpmyadmin" -ForegroundColor Gray
        exit 1
    }
} catch {
    Write-Host "[ERROR] Failed to connect: $_" -ForegroundColor Red
    exit 1
}

# Step 4: Prompt for database name
Write-Host "Step 4: Select database to export..." -ForegroundColor Yellow
Write-Host "-----------------------------------------------------------" -ForegroundColor Gray

if ($dbName) {
    Write-Host "Press ENTER to use suggested database: $dbName" -ForegroundColor Cyan
    Write-Host "Or type a different database name:" -ForegroundColor Cyan
    $userInput = Read-Host
    if ($userInput) {
        $dbName = $userInput
    }
} else {
    Write-Host "Enter the database name to export:" -ForegroundColor Cyan
    $dbName = Read-Host
}

Write-Host ""
Write-Host "[SELECTED] Database: $dbName" -ForegroundColor Green
Write-Host ""

# Step 5: Export database
Write-Host "Step 5: Exporting database..." -ForegroundColor Yellow
Write-Host "-----------------------------------------------------------" -ForegroundColor Gray

$exportFile = "d:\laravel\database_export_$dbName_$(Get-Date -Format 'yyyyMMdd_HHmmss').sql"

try {
    Write-Host "Exporting to: $exportFile" -ForegroundColor Cyan
    & $mysqldumpExe -h $dbHost -P $dbPort -u $dbUser $dbName > $exportFile 2>&1
    
    if ($LASTEXITCODE -eq 0 -and (Test-Path $exportFile)) {
        $fileSize = (Get-Item $exportFile).Length / 1KB
        Write-Host "[SUCCESS] Database exported!" -ForegroundColor Green
        Write-Host "  File: $exportFile" -ForegroundColor Cyan
        Write-Host "  Size: $([math]::Round($fileSize, 2)) KB" -ForegroundColor Cyan
        Write-Host ""
    } else {
        Write-Host "[ERROR] Export failed!" -ForegroundColor Red
        exit 1
    }
} catch {
    Write-Host "[ERROR] Export failed: $_" -ForegroundColor Red
    exit 1
}

# Step 6: Import to d:\laravel (if different database)
Write-Host "Step 6: Import options..." -ForegroundColor Yellow
Write-Host "-----------------------------------------------------------" -ForegroundColor Gray

Write-Host "The database has been exported successfully!" -ForegroundColor Green
Write-Host ""
Write-Host "Next steps:" -ForegroundColor Cyan
Write-Host "  1. The export file is saved at:" -ForegroundColor White
Write-Host "     $exportFile" -ForegroundColor Gray
Write-Host ""
Write-Host "  2. To import into d:\laravel, you can:" -ForegroundColor White
Write-Host "     a) Use phpMyAdmin (http://localhost/phpmyadmin)" -ForegroundColor Gray
Write-Host "     b) Run this command:" -ForegroundColor Gray
Write-Host "        mysql -u root $dbName < `"$exportFile`"" -ForegroundColor DarkGray
Write-Host ""
Write-Host "  3. Update d:\laravel\.env with the database name:" -ForegroundColor White
Write-Host "        DB_CONNECTION=mysql" -ForegroundColor DarkGray
Write-Host "        DB_DATABASE=$dbName" -ForegroundColor DarkGray
Write-Host ""

Write-Host "===========================================================" -ForegroundColor Cyan
Write-Host "Export Complete!" -ForegroundColor Green
Write-Host "===========================================================" -ForegroundColor Cyan

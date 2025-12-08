<#
PowerShell deployment helper for this repository.

Usage examples:
.
# Basic build (no migrations):
#   ./scripts/deploy.ps1
# Run migrations (non-destructive) and attempt a DB backup if mysqldump exists:
#   ./scripts/deploy.ps1 -RunMigrations -BackupDB

This script does NOT run seeders. Migrations will run only if you pass -RunMigrations.
Always backup the DB before running migrations.
#>

param(
    [switch]$RunMigrations,
    [switch]$BackupDB
)

function Parse-EnvFile {
    param([string]$Path)
    $result = @{}
    if (-Not (Test-Path $Path)) { return $result }
    Get-Content $Path | ForEach-Object {
        if ($_ -match '^[\s#]*$') { return }
        if ($_ -match '^\s*#') { return }
        $parts = $_ -split '=',2
        if ($parts.Length -eq 2) {
            $k = $parts[0].Trim()
            $v = $parts[1].Trim()
            # Remove surrounding quotes
            if ($v -match '^".*"$' -or $v -match "^'.*'") { $v = $v.Substring(1, $v.Length-2) }
            $result[$k] = $v
        }
    }
    return $result
}

Write-Host "Starting deploy script..." -ForegroundColor Cyan

$projectRoot = Split-Path -Parent $MyInvocation.MyCommand.Definition
Set-Location $projectRoot

# 1) Optional DB backup using mysqldump (if requested)
if ($BackupDB) {
    Write-Host "Backup requested. Checking for .env and mysqldump..." -ForegroundColor Yellow
    $envMap = Parse-EnvFile -Path (Join-Path $projectRoot '.env')
    $dbHost = $envMap['DB_HOST']      
    $dbPort = $envMap['DB_PORT']      
    $dbDatabase = $envMap['DB_DATABASE']
    $dbUser = $envMap['DB_USERNAME']
    $dbPass = $envMap['DB_PASSWORD']

    if (-Not (Get-Command mysqldump -ErrorAction SilentlyContinue)) {
        Write-Host "Warning: 'mysqldump' not found in PATH. Skipping DB dump." -ForegroundColor Yellow
    } elseif (-not $dbDatabase) {
        Write-Host "DB_DATABASE not found in .env. Skipping DB dump." -ForegroundColor Yellow
    } else {
        $timestamp = Get-Date -Format "yyyy-MM-dd_HH-mm-ss"
        $backupFile = Join-Path $projectRoot "db-backup-$timestamp.sql"
        $dumpCmd = "mysqldump -h $dbHost -P $dbPort -u $dbUser -p`"$dbPass`" $dbDatabase > `"$backupFile`""
        Write-Host "Running: $dumpCmd"
        # Use cmd.exe to allow redirection
        cmd.exe /c $dumpCmd
        if (Test-Path $backupFile) { Write-Host "DB dump saved to $backupFile" -ForegroundColor Green } else { Write-Host "DB dump may have failed." -ForegroundColor Red }
    }
}

# 2) Install PHP deps (composer) in production mode
Write-Host "Installing PHP dependencies (composer)." -ForegroundColor Cyan
if (Test-Path (Join-Path $projectRoot 'composer.json')) {
    & composer install --no-dev --optimize-autoloader --prefer-dist
} else { Write-Host "composer.json not found -- skipping composer install." -ForegroundColor Yellow }

# 3) Install JS deps and build assets
Write-Host "Installing JS dependencies and building assets (npm)." -ForegroundColor Cyan
if (Test-Path (Join-Path $projectRoot 'package.json')) {
    & npm ci
    & npm run build
} else { Write-Host "package.json not found -- skipping npm steps." -ForegroundColor Yellow }

# 4) Laravel maintenance: storage link and caches
Write-Host "Creating storage link and caching config/routes/views." -ForegroundColor Cyan
if (Test-Path (Join-Path $projectRoot 'artisan')) {
    & php artisan storage:link 2>$null
    & php artisan config:cache
    & php artisan route:cache
    & php artisan view:cache
    & php artisan optimize
} else { Write-Host "artisan not found -- skipping artisan commands." -ForegroundColor Yellow }

# 5) Run migrations only if explicitly requested
if ($RunMigrations) {
    Write-Host "Running migrations (--force) as requested." -ForegroundColor Yellow
    & php artisan migrate --force
} else {
    Write-Host "Migrations not requested. To run migrations, re-run with -RunMigrations." -ForegroundColor Green
}

Write-Host "Deploy script finished." -ForegroundColor Cyan

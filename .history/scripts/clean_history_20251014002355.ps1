param(
    [switch]$WhatIf
)

# Cleanup deduplicated timestamped files in .history by keeping the newest and renaming to canonical name
$ErrorActionPreference = 'Stop'
$repoRoot = Split-Path -Parent $MyInvocation.MyCommand.Path
$historyRoot = Join-Path $repoRoot '.history'
$logFile = Join-Path $repoRoot 'cleanup-history.log'

if (-not (Test-Path -LiteralPath $historyRoot)) {
    Write-Host "No .history folder found at $historyRoot. Nothing to do." -ForegroundColor Yellow
    exit 0
}

# Helper to compute canonical filename and extract version
function Get-VersionInfo {
    param([string]$name)
    $version = $null
    $canonical = $name

    # Blade case: name.blade_YYYY... .php => name.blade.php
    if ($name -match '^(?<base>.+?\.blade)_(?<ts>[0-9]{8,})\.php$') {
        $canonical = "$($Matches.base).php"
        $version = [int64]$Matches.ts
        return [pscustomobject]@{ Canonical = $canonical; Version = $version }
    }

    # Generic PHP case: Name_YYYY... .php => Name.php
    if ($name -match '^(?<base>.+?)_(?<ts>[0-9]{8,})\.php$') {
        $canonical = "$($Matches.base).php"
        $version = [int64]$Matches.ts
        return [pscustomobject]@{ Canonical = $canonical; Version = $version }
    }

    # Already canonical or not matching pattern
    return [pscustomobject]@{ Canonical = $name; Version = $null }
}

# Collect candidate files in .history that are PHP and have timestamp suffixes
$allFiles = Get-ChildItem -LiteralPath $historyRoot -Recurse -File -Filter '*.php'
$candidates = @()
foreach ($f in $allFiles) {
    $vi = Get-VersionInfo -name $f.Name
    if ($vi.Version -ne $null -and $vi.Canonical -ne $f.Name) {
        $relDir = $f.DirectoryName.Substring($historyRoot.Length).TrimStart('\\','/')
        $key = Join-Path $relDir $vi.Canonical
        $candidates += [pscustomobject]@{
            File = $f
            Canonical = $vi.Canonical
            Version = $vi.Version
            Key = $key
        }
    }
}

if ($candidates.Count -eq 0) {
    Write-Host 'No timestamped duplicates found in .history.' -ForegroundColor Yellow
    exit 0
}

# Group by canonical key
$groups = $candidates | Group-Object -Property Key

$logLines = @()
$opsRenamed = 0
$opsDeleted = 0

foreach ($g in $groups) {
    # Determine the winner (max Version)
    $winner = $g.Group | Sort-Object -Property Version -Descending | Select-Object -First 1
    $dirPath = $winner.File.DirectoryName
    $canonicalPath = Join-Path $dirPath $winner.Canonical

    # If a canonical file already exists, remove it (we'll replace with the winner)
    if (Test-Path -LiteralPath $canonicalPath) {
        $msg = "REMOVE existing canonical (will replace): $canonicalPath"
        $logLines += $msg
        if ($WhatIf) { Write-Host "[WhatIf] $msg" -ForegroundColor Yellow } else { Remove-Item -LiteralPath $canonicalPath -Force }
        $opsDeleted++
    }

    # Rename winner to canonical if needed
    if ($winner.File.FullName -ne $canonicalPath) {
        $msg = "RENAME keep newest: $($winner.File.FullName) -> $canonicalPath"
        $logLines += $msg
        if ($WhatIf) { Write-Host "[WhatIf] $msg" -ForegroundColor Yellow } else { Move-Item -LiteralPath $winner.File.FullName -Destination $canonicalPath -Force }
        $opsRenamed++
    }

    # Remove the rest duplicates in the group
    foreach ($loser in ($g.Group | Where-Object { $_ -ne $winner })) {
        $msg = "DELETE duplicate: $($loser.File.FullName)"
        $logLines += $msg
        if ($WhatIf) { Write-Host "[WhatIf] $msg" -ForegroundColor Yellow } else { Remove-Item -LiteralPath $loser.File.FullName -Force }
        $opsDeleted++
    }
}

# Write log
$timestamp = Get-Date -Format 'yyyy-MM-dd HH:mm:ss'
$header = "[$timestamp] Cleaned .history: Renamed=$opsRenamed, Deleted=$opsDeleted, Groups=$($groups.Count)"
$logContent = $header + [Environment]::NewLine + ($logLines -join [Environment]::NewLine) + [Environment]::NewLine

if ($WhatIf) {
    Write-Host "[WhatIf] $header" -ForegroundColor Cyan
} else {
    Add-Content -LiteralPath $logFile -Value $logContent
    Write-Host $header -ForegroundColor Green
    Write-Host "Log written to: $logFile" -ForegroundColor DarkGray
}
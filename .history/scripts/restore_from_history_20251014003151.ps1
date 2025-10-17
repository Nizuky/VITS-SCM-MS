# Restores the latest timestamped file versions from .history into the project
# For each file under .history/<dir>/<name>_<YYYYMMDDHHMMSS>.php, copies the newest one
# to <dir>/<name>.php (preserving .blade.php where applicable).
#
# Usage:
#   powershell -NoProfile -ExecutionPolicy Bypass -File scripts/restore_from_history.ps1 -WhatIf
#   powershell -NoProfile -ExecutionPolicy Bypass -File scripts/restore_from_history.ps1

[CmdletBinding(SupportsShouldProcess=$true)]
param(
    [switch]$Quiet
)

Set-StrictMode -Version Latest
$ErrorActionPreference = 'Stop'

# Resolve repo root as the parent of this script directory
$scriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path
$repoRoot = Split-Path -Parent $scriptDir
$historyRoot = Join-Path $repoRoot '.history'

if (-not (Test-Path -LiteralPath $historyRoot)) {
    Write-Error "History folder not found: $historyRoot"
    exit 1
}

# Only allow restoring into these known top-level directories to avoid unintended writes
$allowedTopDirs = @('app','bootstrap','config','database','public','resources','routes','tests','scripts')

# Build a map from target relative path -> object with latest file info
$groups = @{}

# Helper to compute canonical relative path (strip trailing _YYYYMMDDHHMMSS before .php)
function Get-CanonicalPath {
    param(
        [string]$relPath
    )
    # Split directory and filename
    $dir = Split-Path -Parent $relPath
    $name = Split-Path -Leaf $relPath

    # Match names like: something_20251013120305.php OR welcome.blade_20251013120305.php
    $m = [regex]::Match($name, '^(?<base>.+)_(?<stamp>\d{14})\.php$')
    if (-not $m.Success) {
        return $null
    }
    $base = $m.Groups['base'].Value
    $stamp = $m.Groups['stamp'].Value
    $canonicalName = "$base.php"  # if base ends with .blade, this becomes .blade.php

    if ([string]::IsNullOrEmpty($dir)) {
        return @{ RelPath = $canonicalName; Stamp = $stamp }
    } else {
        return @{ RelPath = (Join-Path $dir $canonicalName); Stamp = $stamp }
    }
}

# Enumerate history .php files
$historyFiles = Get-ChildItem -LiteralPath $historyRoot -Recurse -Filter '*.php'

foreach ($file in $historyFiles) {
    # Compute relative path from .history
    # Get relative path from .history and normalize leading separators
    $rel = $file.FullName.Substring($historyRoot.Length) -replace '^[\\/]+',''
    $canon = Get-CanonicalPath -relPath $rel
    if ($null -eq $canon) { continue }

    # Ensure the top-level directory is allowed
    $top = ($canon.RelPath -split '[\\/]')[0]
    if (-not $allowedTopDirs.Contains($top)) { continue }

    $key = $canon.RelPath.ToLowerInvariant()
    $stamp = [int64]$canon.Stamp

    $current = $groups[$key]
    if ($null -eq $current -or $stamp -gt $current.Stamp) {
        $groups[$key] = @{ Stamp = $stamp; Source = $file.FullName; TargetRel = $canon.RelPath }
    }
}

if (-not $Quiet) {
    Write-Host ("Found {0} unique files to restore from .history" -f $groups.Count)
}

# Prepare backup root
$timestamp = (Get-Date).ToString('yyyyMMddHHmmss')
$backupRoot = Join-Path $repoRoot (Join-Path 'backups' ("restore_from_history_" + $timestamp))

# Restore each latest file
$restored = 0
foreach ($entry in $groups.GetEnumerator()) {
    $source = $entry.Value.Source
    $targetRel = $entry.Value.TargetRel
    $target = Join-Path $repoRoot $targetRel

    $targetDir = Split-Path -Parent $target
    if (-not (Test-Path -LiteralPath $targetDir)) {
        if ($PSCmdlet.ShouldProcess($targetDir, 'Create directory')) {
            New-Item -ItemType Directory -Force -Path $targetDir | Out-Null
        }
    }

    $doCopy = $true
    if (Test-Path -LiteralPath $target) {
        # Backup existing target
        $backupPath = Join-Path $backupRoot $targetRel
        $backupDir = Split-Path -Parent $backupPath
        if ($PSCmdlet.ShouldProcess($backupPath, 'Backup existing file')) {
            New-Item -ItemType Directory -Force -Path $backupDir | Out-Null
            Copy-Item -LiteralPath $target -Destination $backupPath -Force
        }
    }

    if ($PSCmdlet.ShouldProcess($target, "Restore from history: $source")) {
        Copy-Item -LiteralPath $source -Destination $target -Force
        $restored++
    }
}

if (-not $Quiet) {
    Write-Host ("Restored {0} files to project from .history" -f $restored)
    Write-Host ("Backups saved under: {0}" -f $backupRoot)
}

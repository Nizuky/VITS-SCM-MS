<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\SuperAdminActivityLog;
use Carbon\Carbon;

echo "Checking activity logs...\n";
echo "Current UTC time: " . now()->toDateTimeString() . "\n";
echo "Current UTC date: " . now()->format('Y-m-d') . "\n\n";

$oct26Start = Carbon::parse('2025-10-26 00:00:00');
$oct26End = Carbon::parse('2025-10-26 23:59:59');
$oct27Start = Carbon::parse('2025-10-27 00:00:00');
$oct27End = Carbon::parse('2025-10-27 23:59:59');

$oct26Count = SuperAdminActivityLog::whereBetween('created_at', [$oct26Start, $oct26End])->count();
$oct27Count = SuperAdminActivityLog::whereBetween('created_at', [$oct27Start, $oct27End])->count();

echo "October 26 UTC (00:00 to 23:59): $oct26Count activities\n";
echo "October 27 UTC (00:00 to 23:59): $oct27Count activities\n\n";

if ($oct27Count > 0) {
    echo "Sample Oct 27 activities:\n";
    $samples = SuperAdminActivityLog::whereBetween('created_at', [$oct27Start, $oct27End])
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get(['action', 'created_at']);
    
    foreach ($samples as $s) {
        echo "  - {$s->action} at {$s->created_at}\n";
    }
}

if ($oct26Count > 0) {
    echo "\nSample Oct 26 activities:\n";
    $samples = SuperAdminActivityLog::whereBetween('created_at', [$oct26Start, $oct26End])
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get(['action', 'created_at']);
    
    foreach ($samples as $s) {
        echo "  - {$s->action} at {$s->created_at}\n";
    }
}

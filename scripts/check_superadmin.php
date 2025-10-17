<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SuperAdmin;
use Illuminate\Support\Facades\Hash;

$a = SuperAdmin::first();
if (! $a) {
    echo "NONE\n";
    exit(0);
}

echo "FOUND: id={$a->id}; name={$a->name}; email={$a->email}\n";
echo 'password_column=' . ($a->password ? 'present' : 'empty') . "\n";
echo (Hash::check('12345678', $a->password) ? "HASH_OK\n" : "HASH_FAIL\n");

// Print full record for debugging
echo json_encode($a->toArray()) . "\n";

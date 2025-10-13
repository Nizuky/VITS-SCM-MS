<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SuperAdmin;

$a = SuperAdmin::first();
if (! $a) {
    echo "No SuperAdmin row found.\n";
    exit(0);
}

$data = [
    'id' => $a->id,
    'name' => $a->name,
    'email' => $a->email,
    'email_verified_at' => $a->email_verified_at ? $a->email_verified_at->toDateTimeString() : null,
    'created_at' => $a->created_at ? $a->created_at->toDateTimeString() : null,
    'updated_at' => $a->updated_at ? $a->updated_at->toDateTimeString() : null,
];

$pw = (string) $a->password;
$isBcrypt = preg_match('/^\$2y\$|^\$2a\$|^\$2b\$/', $pw) === 1;
$data['password_present'] = $pw !== '';
$data['password_looks_bcrypt'] = $isBcrypt;
$data['password_length'] = strlen($pw);

echo json_encode($data, JSON_PRETTY_PRINT) . "\n";

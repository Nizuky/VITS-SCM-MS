<?php

use App\Models\SuperAdmin;
use Illuminate\Support\Facades\Auth;

it('can login seeded superadmin using the superadmin guard', function () {
    // create SuperAdmin in the test database
    $admin = SuperAdmin::create([
        'name' => 'admin2025',
        'email' => 'janarafael.sanandres@gmail.com',
        'password' => bcrypt('12345678'),
        'email_verified_at' => now(),
    ]);

    // attempt to authenticate via the superadmin guard
    Auth::guard('superadmin')->login($admin);

    expect(Auth::guard('superadmin')->check())->toBeTrue();
});

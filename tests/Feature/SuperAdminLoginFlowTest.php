<?php

use App\Models\SuperAdmin;

it('posts superadmin login and redirects to dashboard on success', function () {
    // disable middleware (CSRF) for this test to allow form POST
    $this->withoutMiddleware();

    // create the super admin in the test database
    $admin = SuperAdmin::create([
        'name' => 'admin2025',
        'email' => 'janarafael.sanandres@gmail.com',
        'password' => bcrypt('12345678'),
        'email_verified_at' => now(),
    ]);

    $response = $this->post(route('superadmin.login.submit'), [
        'name' => 'admin2025',
        'password' => '12345678',
    ]);

    $response->assertRedirect(route('superadmin.dashboard'));

    // The test case helpers should now show the guard authenticated
    $this->assertAuthenticatedAs($admin, 'superadmin');
});

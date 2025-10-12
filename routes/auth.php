<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    Volt::route('login', 'auth.login')
        ->name('login');

    Volt::route('register', 'auth.register')
        ->name('register');

    // Super admin login (guest-only)
    Route::get('super-admin/login', function () {
        $admin = App\Models\SuperAdmin::first();
        $defaultName = $admin ? $admin->name : null;
        return view('auth.super-admin-login', ['defaultAdminName' => $defaultName]);
    })->name('superadmin.login');

    Route::post('super-admin/login', App\Http\Controllers\SuperAdmin\LoginController::class)->name('superadmin.login.submit');

    // Super admin password reset request
    Route::get('super-admin/password/reset', function () { return view('auth.super-admin-forgot-password'); })->name('superadmin.password.request');
    Route::post('super-admin/password/email', App\Http\Controllers\SuperAdmin\ForgotPasswordController::class)->name('superadmin.password.email');
    // Reset link with token
    Route::get('super-admin/password/reset/{token}', function ($token) { return view('auth.super-admin-reset-password', ['token' => $token]); })->name('superadmin.password.reset');
    Route::post('super-admin/password/reset', App\Http\Controllers\SuperAdmin\ResetPasswordController::class)->name('superadmin.password.update');

    // role selection flow pages
    Volt::route('choose-role', 'auth.choose-role')
        ->name('choose-role');

    Volt::route('student-exists', 'auth.student-exists')
        ->name('student.exists');

    Volt::route('nonstudent-select', 'auth.nonstudent-select')
        ->name('nonstudent.select');

    Volt::route('forgot-password', 'auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'auth.reset-password')
        ->name('password.reset');

});

Route::middleware('auth')->group(function () {
    Volt::route('verify-email', 'auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    
    // General user logout route (uses invokable Livewire action)
    Route::post('logout', App\Livewire\Actions\Logout::class)
        ->name('logout');
});

// Super-admin protected routes (use superadmin guard)
Route::middleware('auth:superadmin')->group(function () {
    // Redirect to the main super admin dashboard view defined in routes/web.php
    Route::get('super-admin/dashboard', function () {
        return redirect()->route('superadmin.dashboard');
    })->name('superadmin.dashboard');

    // Super-admin logout
    Route::post('super-admin/logout', [App\Http\Controllers\SuperAdmin\LoginController::class, 'logout'])
        ->name('superadmin.logout');
});



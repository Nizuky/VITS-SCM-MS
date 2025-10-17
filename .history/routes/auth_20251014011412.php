<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    Volt::route('login', 'auth.login')
        ->name('login');

    // Other guest-only routes (login/register) are defined here

    // role selection flow pages (traditional Blade views)
    Route::view('choose-role', 'auth.choose-role')
        ->name('choose-role');

    Route::view('student-exists', 'auth.student-exists')
        ->name('student.exists');

    Route::view('nonstudent-select', 'auth.nonstudent-select')
        ->name('nonstudent.select');

    Volt::route('forgot-password', 'auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'auth.reset-password')
        ->name('password.reset');

});

// Make register page reachable even when a user is remembered (avoid RedirectIfAuthenticated)
Volt::route('register', 'auth.register')->name('register');

// Super-admin guest routes (use a guard-specific guest middleware to avoid conflicting with web remember-me)
Route::middleware('guest:superadmin')->group(function () {
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
});

Route::middleware('auth')->group(function () {
    Volt::route('verify-email', 'auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    
    // General user logout route (web guard): logout and return to welcome page
    Route::post('logout', function () {
        \Illuminate\Support\Facades\Auth::guard('web')->logout();
        try { request()->session()->invalidate(); } catch (\Throwable $e) {}
        try { request()->session()->regenerateToken(); } catch (\Throwable $e) {}
        return redirect()->route('home');
    })->name('logout');
});

// Super-admin protected routes (use superadmin guard)
Route::middleware('auth:superadmin')->group(function () {
    // Redirect to the main super admin dashboard view defined in routes/web.php
        Route::get('super-admin/dashboard', function () {
            return view('dashboards.super_admin');
        })->name('superadmin.dashboard');

    // Super-admin logout
    Route::post('super-admin/logout', [App\Http\Controllers\SuperAdmin\LoginController::class, 'logout'])
        ->name('superadmin.logout');
});



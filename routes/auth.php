<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest:web')->group(function () {
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

});

// Make password reset page reachable even if the user is authenticated (used by profile-confirmation flow)
Volt::route('reset-password/{token}', 'auth.reset-password')
    ->middleware('guest:web')
    ->name('password.reset');

// Make register page reachable even when a user is remembered (avoid RedirectIfAuthenticated)
Volt::route('register', 'auth.register')
    ->middleware('guest:web')
    ->name('register');

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

// Signed email verification link should work without requiring authentication
Route::get('email/verify/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::middleware('auth:web')->group(function () {
    // Our custom verification prompt page that includes a resend link
    Volt::route('verify-email', 'auth.verify-email')
        ->name('verification.prompt');

    // Provide Fortify's expected verification.notice name to redirect into our prompt
    Route::get('email/verify', function(){
        return redirect()->route('verification.prompt');
    })->name('verification.notice');

    // General user logout route (web guard): logout and return to welcome page
    Route::post('logout', function () {
        \Illuminate\Support\Facades\Auth::guard('web')->logout();
        try { request()->session()->invalidate(); } catch (\Throwable $e) {}
        try { request()->session()->regenerateToken(); } catch (\Throwable $e) {}
        return redirect()->route('home');
    })->name('logout');

    // Beacon-friendly logout endpoint: allows immediate logout during page/tab close or back navigation.
    // Uses GET to avoid CSRF requirements and is protected by auth middleware.
    // Returns 204 No Content for reliability with keepalive fetch.
    Route::get('logout-beacon', function (\Illuminate\Http\Request $request) {
        \Illuminate\Support\Facades\Auth::guard('web')->logout();
        try { $request->session()->invalidate(); } catch (\Throwable $e) {}
        try { $request->session()->regenerateToken(); } catch (\Throwable $e) {}
        // If this was called via fetch/beacon (expects JSON/XHR), return 204.
        // If a user navigated to this URL directly, redirect to home to avoid a blank page.
        $acceptsJson = $request->expectsJson() || str_contains(strtolower($request->header('accept', '')), 'application/json') || $request->ajax();
        if ($acceptsJson) {
            return response()->noContent(); 
        }
        return redirect()->route('home');
    })->name('logout.beacon');

    // POST beacon endpoint for navigator.sendBeacon (which always sends POST)
    Route::post('logout-beacon', function () {
        \Illuminate\Support\Facades\Auth::guard('web')->logout();
        try { request()->session()->invalidate(); } catch (\Throwable $e) {}
        try { request()->session()->regenerateToken(); } catch (\Throwable $e) {}
        return response()->noContent();
    })->name('logout.beacon.post')
      ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
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

// Admin protected beacon logout routes (separate from web guard)
Route::middleware('auth:admin')->group(function(){
    Route::get('admin/logout-beacon', function (\Illuminate\Http\Request $request) {
        \Illuminate\Support\Facades\Auth::guard('admin')->logout();
        try { $request->session()->invalidate(); } catch (\Throwable $e) {}
        try { $request->session()->regenerateToken(); } catch (\Throwable $e) {}
        $acceptsJson = $request->expectsJson() || str_contains(strtolower($request->header('accept', '')), 'application/json') || $request->ajax();
        if ($acceptsJson) return response()->noContent();
        return redirect()->route('admin.login');
    })->name('admin.logout.beacon');

    Route::post('admin/logout-beacon', function () {
        \Illuminate\Support\Facades\Auth::guard('admin')->logout();
        try { request()->session()->invalidate(); } catch (\Throwable $e) {}
        try { request()->session()->regenerateToken(); } catch (\Throwable $e) {}
        return response()->noContent();
    })->name('admin.logout.beacon.post')
      ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
});



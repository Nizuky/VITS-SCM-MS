<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Auth;
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
        // If already authenticated as superadmin, redirect to dashboard
        if (Auth::guard('superadmin')->check()) {
            return redirect()->route('superadmin.dashboard');
        }
        
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

// Super admin password change verification (signed route)
Route::get('super-admin/password/verify', [App\Http\Controllers\SuperAdmin\SettingsController::class, 'verifyPasswordChange'])
    ->name('superadmin.password.verify');

// Admin password change verification (signed route)
Route::get('admin/password/verify', [App\Http\Controllers\Admin\SettingsController::class, 'verifyPasswordChange'])
    ->name('admin.password.verify');

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
Route::middleware(['auth:superadmin', \App\Http\Middleware\EnsureSuperAdminSessionActive::class])->group(function () {
    // Redirect to the main super admin dashboard view defined in routes/web.php
        Route::get('super-admin/dashboard', function () {
            $BASE_PATH = '';
            return view('dashboards.super_admin', compact('BASE_PATH'));
        })->name('superadmin.dashboard');

    // Super-admin API endpoints
    Route::get('super-admin/api/dashboard-stats', [App\Http\Controllers\SuperAdminDashboardController::class, 'getDashboardStats']);
    Route::get('super-admin/api/submissions', [App\Http\Controllers\SuperAdminDashboardController::class, 'getSubmissions']);
    Route::post('super-admin/api/submissions/{id}/verify', [App\Http\Controllers\SuperAdminDashboardController::class, 'verifySubmission']);
    Route::post('super-admin/api/submissions/{id}/approve', [App\Http\Controllers\SuperAdminDashboardController::class, 'approveSubmission']);
    Route::post('super-admin/api/submissions/{id}/reject', [App\Http\Controllers\SuperAdminDashboardController::class, 'rejectSubmission']);
    Route::delete('super-admin/api/submissions/{id}', [App\Http\Controllers\SuperAdminDashboardController::class, 'deleteSubmission']);
    Route::get('super-admin/api/activity-calendar', [App\Http\Controllers\SuperAdminDashboardController::class, 'getActivityCalendar']);
    Route::get('super-admin/api/activity-details', [App\Http\Controllers\SuperAdminDashboardController::class, 'getActivityDetails']);

    // Super-admin Settings API endpoints
    Route::post('super-admin/api/settings/update-name', [App\Http\Controllers\SuperAdmin\SettingsController::class, 'updateName'])->name('superadmin.settings.updateName');
    Route::post('super-admin/api/settings/request-password-change', [App\Http\Controllers\SuperAdmin\SettingsController::class, 'requestPasswordChange'])->name('superadmin.settings.requestPasswordChange');

    // Super-admin Students Management API endpoints
    Route::get('super-admin/api/students', [App\Http\Controllers\SuperAdminStudentController::class, 'index'])->name('superadmin.students.index');
    Route::put('super-admin/api/students/{id}', [App\Http\Controllers\SuperAdminStudentController::class, 'update'])->name('superadmin.students.update');
    Route::delete('super-admin/api/students/{id}', [App\Http\Controllers\SuperAdminStudentController::class, 'destroy'])->name('superadmin.students.destroy');

    // Super-admin Support Tickets API endpoints
    Route::get('super-admin/api/support-tickets', [App\Http\Controllers\SuperAdminDashboardController::class, 'getSupportTickets'])->name('superadmin.support-tickets.index');
    Route::put('super-admin/api/support-tickets/{id}/status', [App\Http\Controllers\SuperAdminDashboardController::class, 'updateTicketStatus'])->name('superadmin.support-tickets.updateStatus');
    Route::put('super-admin/api/support-tickets/{id}/resolve', [App\Http\Controllers\SuperAdminDashboardController::class, 'resolveTicket'])->name('superadmin.support-tickets.resolve');

    // Super-admin logout
    Route::post('super-admin/logout', [App\Http\Controllers\SuperAdmin\LoginController::class, 'logout'])
        ->name('superadmin.logout');
});

// Super-admin protected beacon logout routes (separate from web guard)
Route::middleware('auth:superadmin')->group(function(){
    Route::get('super-admin/logout-beacon', function (\Illuminate\Http\Request $request) {
        \Illuminate\Support\Facades\Auth::guard('superadmin')->logout();
        try { $request->session()->invalidate(); } catch (\Throwable $e) {}
        try { $request->session()->regenerateToken(); } catch (\Throwable $e) {}
        $acceptsJson = $request->expectsJson() || str_contains(strtolower($request->header('accept', '')), 'application/json') || $request->ajax();
        if ($acceptsJson) return response()->noContent();
        return redirect()->route('superadmin.login');
    })->name('superadmin.logout.beacon');

    Route::post('super-admin/logout-beacon', function () {
        \Illuminate\Support\Facades\Auth::guard('superadmin')->logout();
        try { request()->session()->invalidate(); } catch (\Throwable $e) {}
        try { request()->session()->regenerateToken(); } catch (\Throwable $e) {}
        return response()->noContent();
    })->name('superadmin.logout.beacon.post')
      ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
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



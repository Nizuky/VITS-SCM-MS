<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Student dashboard route (web guard)
Route::view('dashboard', 'dashboards.student')
       ->middleware(['auth:web', 'verified', \App\Http\Middleware\EnsureCorrectGuard::class.':web'])
    ->name('dashboard');

// CSRF cookie preflight: ensures XSRF-TOKEN cookie is set for AJAX
Route::get('/api/csrf-cookie', function (
    \Illuminate\Http\Request $request
) {
    try {
        \Illuminate\Support\Facades\Log::debug('csrf-cookie called', [
            'session_id' => $request->session()->getId(),
            'cookies' => $request->cookies->all(),
            'is_authenticated' => $request->user() ? true : false,
            'path' => $request->getPathInfo(),
        ]);
    } catch (\Throwable $_) { /* ignore logging errors */ }
    // Mirror Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::addCookieToResponse
    $token = $request->session()->token() ?: csrf_token();
    $cookieValue = rawurlencode($token);

    $cookie = cookie(
        'XSRF-TOKEN',
        $cookieValue,
        0,
        config('session.path', '/'),
        config('session.domain', null),
        config('session.secure', false),
        false
    );

    return response()->noContent()->withCookie($cookie);
})->name('csrf.cookie');

// API: Refresh CSRF token (for session keeper)
Route::get('/api/refresh-csrf', function (\Illuminate\Http\Request $request) {
    try {
        $token = $request->session()->token() ?: csrf_token();
        
        // Also ensure session markers are present based on authenticated guard
        if (auth()->guard('admin')->check()) {
            if (!$request->session()->has('admin_session_active')) {
                $request->session()->put('admin_session_active', true);
            }
        } elseif (auth()->guard('superadmin')->check()) {
            if (!$request->session()->has('superadmin_session_active')) {
                $request->session()->put('superadmin_session_active', true);
            }
        }
        
        // Save session to ensure markers persist
        $request->session()->save();
        
        return response()->json(['token' => $token]);
    } catch (\Throwable $e) {
        return response()->json(['error' => 'Failed to refresh token'], 500);
    }
});

// Simple keep-alive endpoint (no CSRF required for GET)
Route::get('/keep-alive', function (\Illuminate\Http\Request $request) {
    if (auth()->guard('web')->check() || 
        auth()->guard('admin')->check() || 
        auth()->guard('superadmin')->check()) {
        $request->session()->put('last_activity', time());
        $request->session()->save();
    }
    return response()->noContent();
});

// Alias for backwards compatibility
Route::get('/refresh-csrf', function (\Illuminate\Http\Request $request) {
    return response()->json(['token' => csrf_token()]);
});

// API: Session keep-alive ping (for all authenticated users)
Route::post('/api/ping', function (\Illuminate\Http\Request $request) {
    try {
        // Touch the session to keep it alive
        $request->session()->put('last_activity', time());
        
        // Determine which guard is authenticated and ensure session marker is present
        $guard = null;
        if (auth()->guard('web')->check()) {
            $guard = 'web';
        } elseif (auth()->guard('admin')->check()) {
            $guard = 'admin';
            // Always ensure admin session marker is present
            $request->session()->put('admin_session_active', true);
        } elseif (auth()->guard('superadmin')->check()) {
            $guard = 'superadmin';
            // Always ensure superadmin session marker is present
            $request->session()->put('superadmin_session_active', true);
        }
        
        // Force save the session to ensure persistence
        $request->session()->save();
        
        return response()->json([
            'status' => 'ok',
            'timestamp' => time(),
            'guard' => $guard,
            'session_id' => $request->session()->getId(),
            'markers_restored' => true
        ]);
    } catch (\Throwable $e) {
        \Log::error('Ping endpoint error', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json(['error' => 'Ping failed'], 500);
    }
});

// Social Contract records API for the authenticated student
Route::middleware(['auth:web', 'verified'])->group(function () {
    // contracts - throttle to prevent abuse (60 requests per minute per user)
    Route::middleware('throttle:60,1')->group(function () {
        Route::get('/api/social-contracts', [\App\Http\Controllers\SocialContractController::class, 'index'])->name('social-contracts.index');
        Route::post('/api/social-contracts', [\App\Http\Controllers\SocialContractController::class, 'store'])->name('social-contracts.store');
        Route::get('/api/social-contract/records', [\App\Http\Controllers\SocialContractRecordController::class, 'index'])->name('social-contract.records.index');
        Route::post('/api/social-contract/records', [\App\Http\Controllers\SocialContractRecordController::class, 'store'])->name('social-contract.records.store');
        Route::delete('/api/social-contract/records/{id}', [\App\Http\Controllers\SocialContractRecordController::class, 'destroy'])->name('social-contract.records.destroy');

        // Student notifications
        Route::get('/api/notifications/recent', [\App\Http\Controllers\StudentNotificationController::class, 'getRecent'])->name('notifications.recent');
        Route::get('/api/notifications/all', [\App\Http\Controllers\StudentNotificationController::class, 'getAll'])->name('notifications.all');
        Route::post('/api/notifications/{id}/read', [\App\Http\Controllers\StudentNotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::delete('/api/notifications/{id}', [\App\Http\Controllers\StudentNotificationController::class, 'delete'])->name('notifications.delete');
        Route::post('/api/notifications/mark-all-read', [\App\Http\Controllers\StudentNotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');

        // Support tickets
        Route::get('/api/support-tickets', [\App\Http\Controllers\SupportTicketController::class, 'index'])->name('support-tickets.index');
        Route::post('/api/support-tickets', [\App\Http\Controllers\SupportTicketController::class, 'store'])->name('support-tickets.store');
        Route::get('/api/support-tickets/{id}', [\App\Http\Controllers\SupportTicketController::class, 'show'])->name('support-tickets.show');
        Route::delete('/api/support-tickets/{id}', [\App\Http\Controllers\SupportTicketController::class, 'destroy'])->name('support-tickets.destroy');
        Route::put('/api/support-tickets/{id}/done', [\App\Http\Controllers\SupportTicketController::class, 'markAsDone'])->name('support-tickets.done');
        Route::get('/api/support-tickets/check-limit', [\App\Http\Controllers\SupportTicketController::class, 'checkLimit'])->name('support-tickets.check-limit');
    });

    // Profile: send password reset link to PLV email with redirect back to profile
    Route::post('/api/profile/send-reset-link', [\App\Http\Controllers\ProfileController::class, 'sendPasswordResetLink'])
        ->name('profile.sendResetLink');
    
    // Profile: update name and/or password
    Route::post('/api/profile/update', [\App\Http\Controllers\ProfileController::class, 'update'])
        ->name('profile.update');
});

// Admin users auth routes (separate from superadmin)
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest-only pages for the admin guard (prevents web-authenticated users from being redirected here)
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('login');
        // Rate limiting handled in controller with better logic
        Route::post('login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'login'])->name('login.submit');

        Route::get('forgot-password', [App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
        Route::post('forgot-password', [App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])
            ->middleware('throttle:3,1')
            ->name('password.email');

        Route::get('reset-password/{token}', [App\Http\Controllers\Admin\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('reset-password', [App\Http\Controllers\Admin\Auth\ResetPasswordController::class, 'reset'])->name('password.update');
    });

    // Authenticated admin pages
    Route::middleware(['auth:admin', \App\Http\Middleware\EnsureAdminSessionActive::class])->group(function () {
        Route::post('logout', [App\Http\Controllers\Admin\Auth\LoginController::class, 'logout'])->name('logout');

        Route::get('dashboard', function () {
            $BASE_PATH = '';
            return view('dashboards.admin', compact('BASE_PATH'));
        })->name('dashboard');

        // Admin API routes for managing student submissions - throttled to prevent abuse
        Route::prefix('api')->name('api.')->middleware('throttle:60,1')->group(function () {
            Route::get('dashboard-stats', [App\Http\Controllers\AdminDashboardController::class, 'getDashboardStats'])->name('dashboard-stats');
            Route::get('submissions', [App\Http\Controllers\AdminDashboardController::class, 'getSubmissions'])->name('submissions');
            Route::post('submissions/{id}/verify', [App\Http\Controllers\AdminDashboardController::class, 'verifySubmission'])->name('submissions.verify');
            Route::post('submissions/{id}/reject', [App\Http\Controllers\AdminDashboardController::class, 'rejectSubmission'])->name('submissions.reject');
            Route::get('activity-calendar', [App\Http\Controllers\AdminDashboardController::class, 'getActivityCalendar'])->name('activity-calendar');
            Route::get('activity-details', [App\Http\Controllers\AdminDashboardController::class, 'getActivityDetails'])->name('activity-details');
            
            // Admin Settings API endpoints
            Route::post('settings/update-name', [App\Http\Controllers\Admin\SettingsController::class, 'updateName'])->name('settings.updateName');
            Route::post('settings/request-password-change', [App\Http\Controllers\Admin\SettingsController::class, 'requestPasswordChange'])->name('settings.requestPasswordChange');
        });

        // Data Management routes
        Route::prefix('data-management')->name('data-management.')->group(function () {
            Route::get('rejected-records', [App\Http\Controllers\Admin\DataManagementController::class, 'getRejectedRecords'])->name('rejected-records');
            Route::get('inactive-accounts', [App\Http\Controllers\Admin\DataManagementController::class, 'getInactiveAccounts'])->name('inactive-accounts');
            Route::delete('records/{id}', [App\Http\Controllers\Admin\DataManagementController::class, 'deleteRecord'])->name('delete-record');
            Route::delete('accounts/{id}', [App\Http\Controllers\Admin\DataManagementController::class, 'deleteAccount'])->name('delete-account');
            Route::post('delete-all-records', [App\Http\Controllers\Admin\DataManagementController::class, 'deleteAllEligibleRecords'])->name('delete-all-records');
            Route::post('delete-all-accounts', [App\Http\Controllers\Admin\DataManagementController::class, 'deleteAllEligibleAccounts'])->name('delete-all-accounts');
        });
    });
});

require __DIR__.'/auth.php';

// Fallback: serve files from storage/app/public via /storage/* if symlink is missing
// This ensures assets like asset('storage/vits_bg.png') work on environments without artisan storage:link
Route::get('storage/{path}', function (string $path) {
    $fullPath = storage_path('app/public/'.str_replace('..', '', $path));

    if (!\Illuminate\Support\Facades\File::exists($fullPath)) {
        abort(404);
    }

    return response()->file($fullPath);
})->where('path', '.*');

// Non-conflicting assets proxy: serve storage/app/public via /assets/*
// Useful when the web server serves public/storage statically and bypasses PHP routes
Route::get('assets/{path}', function (string $path) {
    $fullPath = storage_path('app/public/'.str_replace('..', '', $path));

    if (!\Illuminate\Support\Facades\File::exists($fullPath)) {
        abort(404);
    }

    return response()->file($fullPath);
})->where('path', '.*');

<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
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

// Social Contract records API for the authenticated student
Route::middleware(['auth', 'verified'])->group(function () {
    // contracts
    Route::get('/api/social-contracts', [\App\Http\Controllers\SocialContractController::class, 'index'])->name('social-contracts.index');
    Route::post('/api/social-contracts', [\App\Http\Controllers\SocialContractController::class, 'store'])->name('social-contracts.store');
    Route::get('/api/social-contract/records', [\App\Http\Controllers\SocialContractRecordController::class, 'index'])->name('social-contract.records.index');
    Route::post('/api/social-contract/records', [\App\Http\Controllers\SocialContractRecordController::class, 'store'])->name('social-contract.records.store');
    Route::delete('/api/social-contract/records/{id}', [\App\Http\Controllers\SocialContractRecordController::class, 'destroy'])->name('social-contract.records.destroy');
});

// Admin users auth routes (separate from superadmin)
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest-only pages for the admin guard (prevents web-authenticated users from being redirected here)
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'login'])->name('login.submit');

        Route::get('forgot-password', [App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
        Route::post('forgot-password', [App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

        Route::get('reset-password/{token}', [App\Http\Controllers\Admin\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('reset-password', [App\Http\Controllers\Admin\Auth\ResetPasswordController::class, 'reset'])->name('password.update');
    });

    // Authenticated admin pages
    Route::middleware(['auth:admin', \App\Http\Middleware\EnsureAdminSessionActive::class])->group(function () {
        Route::post('logout', [App\Http\Controllers\Admin\Auth\LoginController::class, 'logout'])->name('logout');

        Route::get('dashboard', function () {
            return view('dashboards.admin');
        })->name('dashboard');
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

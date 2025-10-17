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

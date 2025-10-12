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

// Role-specific dashboards (placeholders)
Route::middleware(['auth'])->group(function () {
    Route::view('student/dashboard', 'dashboards.student')->name('student.dashboard');
    Route::view('admin/dashboard', 'dashboards.admin')->name('admin.dashboard');
    Route::view('super-admin/dashboard', 'dashboards.super_admin')->name('superadmin.dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});



require __DIR__.'/auth.php';

// Simple test route to send the MyTestEmail mailable to the VITS SCMS inbox.
Route::get('/testroute', function () {
    $name = 'VITS Test';

    \Illuminate\Support\Facades\Mail::to('vitsscmms@gmail.com')->send(new \App\Mail\MyTestEmail());

    return 'Test email dispatched to vitsscmms@gmail.com';
});
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Volt\Volt;

class VoltServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Mount default Volt component directories (resources/views/livewire and resources/views/pages)
        // Leaving empty uses Volt's defaults based on config('view.paths').
        Volt::mount();
    }
}

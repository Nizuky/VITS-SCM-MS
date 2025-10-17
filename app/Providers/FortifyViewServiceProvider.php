<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\VerifyEmailViewResponse;

class FortifyViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind Fortify's VerifyEmailViewResponse to redirect to our custom verification prompt route.
        $this->app->bind(VerifyEmailViewResponse::class, function () {
            return new class implements VerifyEmailViewResponse {
                public function toResponse($request)
                {
                    // Our custom route that shows a resend link (Volt route)
                    return redirect()->route('verification.prompt');
                }
            };
        });
    }
}

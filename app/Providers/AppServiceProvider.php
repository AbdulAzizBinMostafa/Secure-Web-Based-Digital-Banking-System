<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use App\Listeners\RecordSecurityLogin;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ABDUL AZIZ: Strict Rate Limiting for Financial Transactions
        RateLimiter::for('transfers', function (Request $request) {
            // Limit to 5 transfers per minute per user
            return Limit::perMinute(5)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('otp_requests', function (Request $request) {
            // Limit OTP generation to 3 per 10 minutes to stop spam
            return Limit::perMinute(3)->by($request->user()?->id ?: $request->ip());
        });

        // MAHIM: Register Login Event Listener for Security Logs
        Event::listen(
            Login::class,
            RecordSecurityLogin::class,
        );
    }
}
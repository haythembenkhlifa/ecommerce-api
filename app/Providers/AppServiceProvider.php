<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Passport::tokensExpireIn(now()->addMinutes(config('passport.token_expires_in', 60 * 24 * 15)));
        Passport::refreshTokensExpireIn(now()->addMinutes(config('passport.refresh_token_expires_in', 60 * 24 * 15)));
        Passport::personalAccessTokensExpireIn(now()->addMinutes(config('passport.personal_access_token_expires_in', 60 * 24 * 15)));
    }
}

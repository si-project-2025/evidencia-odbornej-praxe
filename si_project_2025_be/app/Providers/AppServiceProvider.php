<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

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
        Schema::defaultStringLength(191);

        // Definícia OAuth scopes
        Passport::tokensCan([
            'internship:defend' => 'Zmena stavu praxe na obhájenú',
        ]);
        // Expirácie
        Passport::tokensExpireIn(now()->addDays(15));
    }
}

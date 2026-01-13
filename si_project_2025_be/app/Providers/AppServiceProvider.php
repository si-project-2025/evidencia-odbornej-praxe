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

        Passport::tokensCan([
            'internship:write' => 'Zmena stavu praxe na obhájenú alebo neobhájenú',
            'internship:read' => 'Získanie id vytvorených praxí',
        ]);
        Passport::tokensExpireIn(now()->addDays(15));
    }
}

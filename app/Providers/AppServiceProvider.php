<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        //
        $this->loadMigrationsFrom([
            database_path('migrations/proyecto_qr'),
            database_path('migrations/pamp'),
            database_path('migrations/rep_metro'),
        ]);
    }
}

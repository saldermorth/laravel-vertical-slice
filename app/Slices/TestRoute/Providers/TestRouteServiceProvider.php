<?php

namespace App\Slices\TestRoute\Providers;

use Illuminate\Support\ServiceProvider;

class TestRouteServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register any bindings for this slice
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');

        // Load views with a specific namespace
        $this->loadViewsFrom(__DIR__ . '/../Views', 'TestRoute');
    }
}
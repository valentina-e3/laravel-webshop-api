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
        $this->app->singleton(PriceResolutionService::class, function ($app) {
            return new PriceResolutionService();
        });

        $this->app->singleton(PriceModifierService::class, function ($app) {
            return new PriceModifierService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

<?php

namespace App\Providers;

use App\Services\OrderService;
use App\Services\PriceModifierService;
use App\Services\PriceResolutionService;
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

        $this->app->singleton(OrderService::class, function ($app) {
            return new OrderService(
                $app->make(PriceResolutionService::class),
                $app->make(PriceModifierService::class)
            );
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

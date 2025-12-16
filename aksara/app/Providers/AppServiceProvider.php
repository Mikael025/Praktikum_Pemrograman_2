<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\StatusWorkflowService;
use App\Services\DocumentService;
use App\Services\StatisticsService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register StatusWorkflowService as singleton
        // Service akan di-instantiate sekali dan reused untuk semua requests
        $this->app->singleton(StatusWorkflowService::class, function ($app) {
            return new StatusWorkflowService();
        });

        // Register DocumentService as singleton
        $this->app->singleton(DocumentService::class, function ($app) {
            return new DocumentService();
        });

        // Register StatisticsService as singleton
        $this->app->singleton(StatisticsService::class, function ($app) {
            return new StatisticsService();
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

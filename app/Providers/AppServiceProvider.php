<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\ProductDataHandlerInterface;
use App\Interfaces\CategoryDataHandlerInterface;
use App\Services\ProductDataHandler;
use App\Services\CategoryDataHandler;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProductDataHandlerInterface::class, ProductDataHandler::class);
        $this->app->bind(CategoryDataHandlerInterface::class, CategoryDataHandler::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

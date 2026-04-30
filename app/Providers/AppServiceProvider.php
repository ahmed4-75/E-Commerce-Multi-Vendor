<?php

namespace App\Providers;

use App\Repositories\CategoryRepository;
use App\Repositories\Contracts\CategoryInterface;
use App\Repositories\Contracts\ShopInterface;
use App\Repositories\ShopRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            CategoryInterface::class,
            CategoryRepository::class,
        );

        $this->app->bind(
            ShopInterface::class,
            ShopRepository::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

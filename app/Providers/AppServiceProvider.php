<?php

namespace App\Providers;

use App\Repositories\CategoryRepository;
use App\Repositories\Contracts\CategoryInterface;
use App\Repositories\Contracts\CommentInterface;
use App\Repositories\Contracts\ProductInterface;
use App\Repositories\Contracts\ShopInterface;
use App\Repositories\ProductRepository;
use App\Repositories\ShopRepository;
use App\Repositories\CommentRepository;
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
            CategoryRepository::class
        );

        $this->app->bind(
            ShopInterface::class,
            ShopRepository::class
        );

        $this->app->bind(
            ProductInterface::class,
            ProductRepository::class
        );

        $this->app->bind(
            CommentInterface::class,
            CommentRepository::class
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

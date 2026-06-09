<?php

namespace App\Providers;

use App\Repositories\CartRepository;
use App\Repositories\Contracts\CartInterface;
use App\Repositories\CategoryRepository;
use App\Repositories\Contracts\CategoryInterface;
use App\Repositories\CommentRepository;
use App\Repositories\Contracts\CommentInterface;
use App\Repositories\OrderRepository;
use App\Repositories\Contracts\OrderInterface;
use App\Repositories\PaymentGatewayRepository;
use App\Repositories\Contracts\PaymentGatewayInterface;
use App\Repositories\ProductRepository;
use App\Repositories\Contracts\ProductInterface;
use App\Repositories\ShopRepository;
use App\Repositories\Contracts\ShopInterface;
use App\Repositories\TranslationRepository;
use App\Repositories\Contracts\TranslationInterface;
use App\Repositories\UserRepository;
use App\Repositories\Contracts\UserInterface;
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

        $this->app->bind(
            CartInterface::class,
            CartRepository::class
        );

        $this->app->bind(
            OrderInterface::class,
            OrderRepository::class
        );

        $this->app->bind(
            TranslationInterface::class,
            TranslationRepository::class
        );

        $this->app->bind(
            PaymentGatewayInterface::class,
            PaymentGatewayRepository::class,
        );

        $this->app->bind(
            UserInterface::class,
            UserRepository::class
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

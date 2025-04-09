<?php

namespace App\Providers;

use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Infrastructure\Repositories\UserRepositoryEloquent;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register UserRepository
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepositoryEloquent::class
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

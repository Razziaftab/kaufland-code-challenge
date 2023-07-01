<?php

namespace App\Providers;

use App\Repositories\ItemRepoInterface;
use App\Repositories\ItemRepo;
use Illuminate\Support\ServiceProvider;

class ItemRepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ItemRepoInterface::class, ItemRepo::class);
    }
}

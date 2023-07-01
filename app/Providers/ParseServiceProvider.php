<?php

namespace App\Providers;

use App\Services\ParseServiceInterface;
use App\Services\XMLParseService;
use Illuminate\Support\ServiceProvider;

class ParseServiceProvider extends ServiceProvider
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
        $this->app->bind(ParseServiceInterface::class, XMLParseService::class);
    }
}

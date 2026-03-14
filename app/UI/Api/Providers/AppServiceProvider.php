<?php

namespace App\UI\Api\Providers;

use App\UI\Api\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->singleton(ExceptionHandler::class, Handler::class);

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}

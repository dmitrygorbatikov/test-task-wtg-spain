<?php

declare(strict_types=1);

namespace App\Domains\PersonalAccessToken\Providers;

use Illuminate\Support\ServiceProvider;

class PersonalAccessTokenServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    public function register(): void
    {
    }
}

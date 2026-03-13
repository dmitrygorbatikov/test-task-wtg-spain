<?php

declare(strict_types=1);

namespace App\Domains\AuthenticationCode\Providers;

use Illuminate\Support\ServiceProvider;

final class AuthenticationCodeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    public function register(): void
    {
    }
}

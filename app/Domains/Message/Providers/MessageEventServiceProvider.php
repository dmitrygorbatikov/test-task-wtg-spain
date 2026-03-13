<?php

declare(strict_types=1);

namespace App\Domains\Message\Providers;

use App\Domains\Message\Models\Message;
use App\Domains\Message\Observers\MessageObserver;
use App\Infrastructure\Providers\EventServiceProvider;

class MessageEventServiceProvider extends EventServiceProvider
{

    public function boot(): void
    {
        Message::observe(MessageObserver::class);
    }
}

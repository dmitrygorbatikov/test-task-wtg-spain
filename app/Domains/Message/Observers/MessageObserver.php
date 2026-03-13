<?php

declare(strict_types=1);

namespace App\Domains\Message\Observers;

use App\Domains\Message\Models\Message;
use App\UI\Broadcasting\Events\Message\MessageSentEvent;
use App\UI\Broadcasting\Events\Message\NewChatMessageEvent;

readonly class MessageObserver
{
    public function created(Message $message): void
    {
        broadcast(new MessageSentEvent($message))->toOthers();
        broadcast(new NewChatMessageEvent($message))->toOthers();
    }
}

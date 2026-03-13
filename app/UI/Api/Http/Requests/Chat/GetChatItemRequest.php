<?php

declare(strict_types=1);

namespace App\UI\Api\Http\Requests\Chat;

use App\UI\Api\Http\Requests\BaseRequest;

class GetChatItemRequest extends BaseRequest
{
    public function rules():array
    {
        return [
            'chatId' => ['required', 'int', 'min:1'],
        ];
    }

    public function getChatId(): int
    {
        return (int) $this->validated('chatId');
    }
}

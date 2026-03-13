<?php

declare(strict_types=1);

namespace App\UI\Api\Http\Requests\Message;

use App\UI\Api\Http\Requests\BaseRequest;

class ReadMessagesRequest extends BaseRequest
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

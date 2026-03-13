<?php

declare(strict_types=1);

namespace App\UI\Api\Http\Requests\Message;

use App\UI\Api\Http\Requests\BaseRequest;
use App\Domains\Message\Dto\MessageData;

class CreateMessageRequest extends BaseRequest
{
    public function rules():array
    {
        return [
            'content' => ['required', 'string', 'max:2048'],
            'chatId' => ['required', 'int', 'min:1'],
        ];
    }

    public function getData(): MessageData
    {
        return MessageData::from([...$this->validated()]);
    }
}

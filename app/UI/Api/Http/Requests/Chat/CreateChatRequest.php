<?php

declare(strict_types=1);

namespace App\UI\Api\Http\Requests\Chat;

use App\UI\Api\Http\Requests\BaseRequest;
use App\Domains\Chat\Dto\ChatData;

class CreateChatRequest extends BaseRequest
{
    public function rules():array
    {
        return [
            'secondId' => ['required', 'int', 'min:1'],
            'message' => ['required', 'string', 'max:2048'],
        ];
    }

    public function getData(): ChatData
    {
        return ChatData::from([...$this->validated()]);
    }
}

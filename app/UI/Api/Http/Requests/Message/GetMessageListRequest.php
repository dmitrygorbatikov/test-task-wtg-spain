<?php

declare(strict_types=1);

namespace App\UI\Api\Http\Requests\Message;

use App\Infrastructure\Traits\{Request\CollectsRulesFromTraits,
    Request\HasPaginationParameter,
    Request\HasSearchFilterParameter
};
use App\UI\Api\Http\Requests\BaseRequest;

class GetMessageListRequest extends BaseRequest
{
    use CollectsRulesFromTraits;
    use HasPaginationParameter;
    use HasSearchFilterParameter;

    public function rules(): array
    {
        return [
            ...$this->collectRules(),
            'chatId' => ['required', 'int', 'min:1'],
        ];
    }

    public function getChatId(): int
    {
        return (int) $this->validated('chatId');
    }
}

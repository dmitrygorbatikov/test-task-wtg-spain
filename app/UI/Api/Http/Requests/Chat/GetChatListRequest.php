<?php

declare(strict_types=1);

namespace App\UI\Api\Http\Requests\Chat;

use App\Infrastructure\Traits\{Request\CollectsRulesFromTraits,
    Request\HasPaginationParameter,
    Request\HasSearchFilterParameter
};
use App\UI\Api\Http\Requests\BaseRequest;

class GetChatListRequest extends BaseRequest
{
    use CollectsRulesFromTraits;
    use HasPaginationParameter;
    use HasSearchFilterParameter;

    public function rules(): array
    {
        return $this->collectRules();
    }
}

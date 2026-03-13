<?php

declare(strict_types=1);

namespace App\UI\Api\Http\Requests\User;

use App\Domains\User\Dto\FilterParametersData;
use App\Infrastructure\Traits\{Request\CollectsRulesFromTraits,
    Request\HasPaginationParameter,
    Request\HasSearchFilterParameter
};
use App\UI\Api\Http\Requests\BaseRequest;

class GetAuthorizedUsersRequest extends BaseRequest
{
    use CollectsRulesFromTraits;
    use HasPaginationParameter;
    use HasSearchFilterParameter;

    public function rules(): array
    {
        return $this->collectRules();
    }

    public function getFilter(): ?FilterParametersData
    {
        $filter = $this->validated('filter');

        return $filter ? FilterParametersData::from($filter) : null;
    }
}

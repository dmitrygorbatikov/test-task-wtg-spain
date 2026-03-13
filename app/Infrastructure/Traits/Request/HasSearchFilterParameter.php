<?php

declare(strict_types=1);

namespace App\Infrastructure\Traits\Request;

use Illuminate\Foundation\Http\FormRequest;
use App\Infrastructure\Dto\FilterParametersData;

/**
 * @property array $filter
 *
 * @mixin FormRequest
 */
trait HasSearchFilterParameter
{
    public function searchFilterExtensionRules(): array
    {
        return [
            'filter' => ['bail', 'sometimes', 'array'],
            'filter.search' => ['bail', 'sometimes', 'nullable', 'string', 'max:256'],
        ];
    }

    public function getFilter(): ?FilterParametersData
    {
        $filter = $this->validated('filter');

        return $filter ? FilterParametersData::from($filter) : null;
    }
}

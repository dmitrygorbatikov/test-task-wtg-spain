<?php

declare(strict_types=1);

namespace App\Infrastructure\Traits\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use App\Infrastructure\Dto\PaginateData;

/**
 * @property array $pagination
 *
 * @mixin FormRequest
 */
trait HasPaginationParameter
{
    /**
     * @internal
     */
    public function paginationExtensionRules(): array
    {
        return [
            'pagination' => ['bail', 'sometimes', 'array', 'size:2'],
            'pagination.0' => ['bail', 'required_with:pagination', 'int', 'min:1'],
            'pagination.1' => ['bail', 'required_with:pagination', 'int', 'min:1', 'max:1000'],
        ];
    }

    public function getPage(): int
    {
        return (int) Arr::get($this->pagination, 0, 1);
    }

    public function getPerPage(): int
    {
        return (int) Arr::get($this->pagination, 1, 10);
    }

    public function getPaginateData(): PaginateData
    {
        return new PaginateData(page: $this->getPage(), perPage: $this->getPerPage());
    }
}

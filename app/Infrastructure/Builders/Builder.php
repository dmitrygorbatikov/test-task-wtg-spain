<?php

declare(strict_types=1);

namespace App\Infrastructure\Builders;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Infrastructure\Dto\{PaginateData, SortingParametersData};
use App\Infrastructure\Models\Model;
use App\Infrastructure\Traits\Eloquent\PrepareQueryTrait;

/**
 * @template TModel of Model
 *
 * @template-extends EloquentBuilderAdapter<TModel>
 */
class Builder extends EloquentBuilderAdapter
{
    use PrepareQueryTrait;

    public function applyILikeSearch(?string $search, array $columns): static
    {
        if (!$search) {
            return $this;
        }

        $this->where(function (self $query) use ($search, $columns): void {
            $search = $this->prepareSearchValue($search);

            foreach ($columns as $column) {
                $query->orWhere($column, 'iLike', $search);
            }
        });

        return $this;
    }

    public function possibleWhere(string $column, mixed $value): static
    {
        if (!$value) {
            return $this;
        }

        $this->where($column, '=', $value);

        return $this;
    }

    public function possibleWhereIn(string $column, array $values): static
    {
        if (!$values) {
            return $this;
        }

        $this->whereIn($column, $values);

        return $this;
    }

    public function sortByData(?SortingParametersData $data): static
    {
        if (!$data) {
            return $this;
        }

        $this->orderByRaw("{$data->getColumn()} {$data->getOrder()} NULLS LAST");

        return $this;
    }

    public function getPaginator(PaginateData $paginateData): LengthAwarePaginator
    {
        return $this->paginate(perPage: $paginateData->perPage, page: $paginateData->page);
    }
}

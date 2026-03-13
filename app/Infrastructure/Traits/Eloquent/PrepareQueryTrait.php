<?php

declare(strict_types=1);

namespace App\Infrastructure\Traits\Eloquent;

trait PrepareQueryTrait
{
    protected function prepareSearchValue(string $value): string
    {
        return '%' . $this->prepareFilterValue($value) . '%';
    }

    protected function prepareFilterValue(string $value): string
    {
        return mb_trim(addcslashes($value, '%_'));
    }
}

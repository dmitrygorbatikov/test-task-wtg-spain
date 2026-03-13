<?php

declare(strict_types=1);

namespace App\Infrastructure\Dto;

use Illuminate\Support\Str;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data as SpatieData;

class SortingParametersData extends SpatieData
{
    public function __construct(
        #[MapInputName(0)]
        public string $column,
        #[MapInputName(1)]
        public string $order,
    ) {
    }

    public function getColumn(): string
    {
        return Str::of($this->column)->snake()->lower()->toString();
    }

    public function getOrder(): string
    {
        return Str::lower($this->order);
    }
}

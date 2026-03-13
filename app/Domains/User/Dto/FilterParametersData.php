<?php

declare(strict_types=1);

namespace App\Domains\User\Dto;

use Spatie\LaravelData\Data;

class FilterParametersData extends Data
{
    public function __construct(
        public ?string $search,
    ) {
    }
}

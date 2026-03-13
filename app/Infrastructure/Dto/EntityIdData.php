<?php

declare(strict_types=1);

namespace App\Infrastructure\Dto;

use Spatie\LaravelData\Data;

class EntityIdData extends Data
{
    public function __construct(
        public readonly int $id
    ) {
    }
}

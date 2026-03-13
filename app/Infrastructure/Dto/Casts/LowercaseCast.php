<?php

declare(strict_types=1);

namespace App\Infrastructure\Dto\Casts;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class LowercaseCast implements Cast
{
    public function cast(
        DataProperty $property,
        mixed $value,
        array $properties,
        CreationContext $context
    ): mixed {
        return $value !== null ? mb_strtolower((string) $value) : null;
    }
}

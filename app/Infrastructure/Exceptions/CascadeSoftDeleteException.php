<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CascadeSoftDeleteException extends LogicException
{
    public static function softDeleteNotImplemented(string $class): CascadeSoftDeleteException
    {
        return new self(sprintf('%s does not implement %s', $class, SoftDeletes::class));
    }

    public static function invalidRelationships(array $relationships): CascadeSoftDeleteException
    {
        return new self(sprintf(
            '%s [%s] must exist and return an object of type %s',
            Str::plural('Relationship', count($relationships)),
            implode(', ', $relationships),
            Relation::class
        ));
    }
}

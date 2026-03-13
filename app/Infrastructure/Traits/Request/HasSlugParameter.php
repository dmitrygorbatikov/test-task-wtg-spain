<?php

declare(strict_types=1);

namespace App\Infrastructure\Traits\Request;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @mixin FormRequest
 */
trait HasSlugParameter
{
    public function getSlugParameterRule(string $field, bool $isRequired = true): array
    {
        return [
            $field => ['bail', $isRequired ? 'required' : 'sometimes', 'string', 'max:255'],
        ];
    }

    public function getSlug(string $field): string
    {
        return (string) $this->validated($field);
    }
}

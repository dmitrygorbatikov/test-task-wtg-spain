<?php

declare(strict_types=1);

namespace App\Infrastructure\Traits\Request;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @mixin FormRequest
 */
trait HasIdParameter
{
    public function getIdParameterRule(string $field, bool $isRequired = true): array
    {
        return [
            $field => ['bail', $isRequired ? 'required' : 'sometimes', 'int', 'min:1'],
        ];
    }

    public function getId(string $field): int
    {
        return (int) $this->validated($field);
    }
}

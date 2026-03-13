<?php

declare(strict_types=1);

namespace App\Infrastructure\Traits\Request;

use Illuminate\Foundation\Http\FormRequest;
use App\Infrastructure\Http\Requests\BaseRequest;

/**
 * @mixin FormRequest
 */
trait HasEmail
{
    public function getEmailRulesByField(
        string $field,
        array $rules = ['sometimes'],
        int $min = 6,
        int $max = 254,
        string $regex = BaseRequest::EMAIL_REGEX
    ): array {
        return [
            $field => ['bail', ...$rules, 'string', "between:{$min},{$max}", "regex:{$regex}"],
        ];
    }

    public function getEmailByField(string $field): ?string
    {
        return $this->validated($field);
    }
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\Traits\Request;

use Illuminate\Foundation\Http\FormRequest;
use App\Infrastructure\Http\Requests\BaseRequest;

/**
 * @mixin FormRequest
 */
trait HasPasswordParameter
{
    public function getPasswordRulesByField(
        string $field = 'password',
        array $rules = ['required'],
        int $min = 8,
        int $max = 64,
        string $regex = BaseRequest::PASSWORD_REGEX
    ): array {
        return [
            $field => ['bail', ...$rules, 'string', "between:{$min},{$max}", 'regex:' . $regex],
        ];
    }

    public function getPasswordByField(string $field = 'password'): ?string
    {
        return $this->validated($field);
    }

    public function getPasswordValidationMessages(string $field = 'password'): array
    {
        return [
            "{$field}.regex" => __('api/errors.validation.password_format'),
            "{$field}.between" => __('api/errors.validation.password_length'),
        ];
    }
}

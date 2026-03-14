<?php

declare(strict_types=1);

namespace App\UI\Api\Http\Requests\Auth;

use App\Domains\User\Dto\UserData;
use App\Infrastructure\Traits\Request\{HasEmail,HasPasswordParameter};
use App\UI\Api\Http\Requests\BaseRequest;

class LoginUserByEmailRequest extends BaseRequest
{
    use HasEmail;
    use HasPasswordParameter;

    public function rules(): array
    {
        return [
            ...$this->getEmailRulesByField(field: 'email', rules: ['required'], max: 256),
            ...$this->getPasswordRulesByField(),
        ];
    }

    public function messages(): array
    {
        return $this->getPasswordValidationMessages();
    }

    public function getData(): UserData
    {
        return UserData::from([
            ...$this->validated(),
        ]);
    }
}

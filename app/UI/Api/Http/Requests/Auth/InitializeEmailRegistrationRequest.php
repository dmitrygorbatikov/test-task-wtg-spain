<?php

declare(strict_types=1);

namespace App\UI\Api\Http\Requests\Auth;

use App\Domains\User\Dto\UserData;
use App\Infrastructure\Traits\Request\{HasEmail,HasPasswordParameter};
use App\UI\Api\Http\Requests\BaseRequest;

class InitializeEmailRegistrationRequest extends BaseRequest
{
    use HasEmail;
    use HasPasswordParameter;

    public function rules(): array
    {
        return [
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            ...$this->getEmailRulesByField(field: 'email', rules: ['required'], max: 256),
            ...$this->getPasswordRulesByField(),
        ];
    }

    public function getData(): UserData
    {
        return UserData::from([
            ...$this->validated(),
        ]);
    }
}

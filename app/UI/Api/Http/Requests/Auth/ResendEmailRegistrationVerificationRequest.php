<?php

declare(strict_types=1);

namespace App\UI\Api\Http\Requests\Auth;

use App\Domains\AuthenticationCode\Enums\AuthenticationCodePurpose;
use Illuminate\Validation\Rule;
use App\UI\Api\Http\Requests\BaseRequest;

class ResendEmailRegistrationVerificationRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'email' => ['bail', 'required', 'string', 'between:6,254', 'regex:' . self::EMAIL_LOCAL_PART_MAX_64_REGEX],
            'codePurpose' => ['bail', 'required', 'array'],
            'codePurpose.id' => [
                'bail',
                'required',
                'string',
                Rule::in([
                    AuthenticationCodePurpose::UserRecoverPassword,
                    AuthenticationCodePurpose::UserFinishEmailRegistrationWithConfirmationCode,
                    AuthenticationCodePurpose::UserFinishSocialRegistration,
                ]),
            ],
        ];
    }

    public function getEmail(): string
    {
        return $this->validated('email');
    }

    public function getCodePurpose(): AuthenticationCodePurpose
    {
        return AuthenticationCodePurpose::from($this->validated('codePurpose.id'));
    }
}

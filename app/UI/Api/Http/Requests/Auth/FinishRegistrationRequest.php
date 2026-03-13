<?php

declare(strict_types=1);

namespace App\UI\Api\Http\Requests\Auth;

use App\Domains\PersonalAccessToken\Dto\CodeIdentifierWithMailCodeData;
use App\Domains\AuthenticationCode\Enums\AuthenticationCodePurpose;
use App\Domains\AuthenticationCode\Models\AuthenticationCode;
use Illuminate\Validation\Rule;
use App\UI\Api\Http\Requests\BaseRequest;

class FinishRegistrationRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'code' => ['bail', 'required', 'string', 'size:' . AuthenticationCode::EMAIL_CODE_SIZE],
            'codeIdentifier' => ['bail', 'required', 'uuid'],
            'codePurpose' => ['bail', 'required', 'array'],
            'codePurpose.id' => [
                'bail',
                'required',
                'string',
                Rule::in([
                    AuthenticationCodePurpose::UserFinishEmailRegistrationWithConfirmationCode,
                    AuthenticationCodePurpose::UserFinishSocialRegistration,
                ]),
            ],
        ];
    }

    public function getData(): CodeIdentifierWithMailCodeData
    {
        return CodeIdentifierWithMailCodeData::from($this->validated());
    }

    public function getCodePurpose(): AuthenticationCodePurpose
    {
        return AuthenticationCodePurpose::from($this->validated('codePurpose.id'));
    }
}

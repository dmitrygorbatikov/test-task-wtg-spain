<?php

declare(strict_types=1);

namespace App\Domains\AuthenticationCode\Enums;

use App\Infrastructure\Interfaces\Enum\EnumInterface;
use App\Infrastructure\Traits\Enum\EnumsTrait;

enum AuthenticationCodePurpose: string implements EnumInterface
{
    use EnumsTrait;

    case UserFinishEmailRegistration = 'user_finish_email_registration';
    case UserFinishEmailRegistrationWithConfirmationCode = 'user_finish_email_registration_with_confirmation_code';
    case UserFinishSocialRegistration = 'user_finish_social_registration';
    case UserRecoverPassword = 'user_recover_password';
}

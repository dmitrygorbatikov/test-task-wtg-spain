<?php

declare(strict_types=1);

namespace App\Application\AuthenticationCode\Actions;

use App\Application\Auth\Mail\ConfirmationCodeMail;
use App\Domains\AuthenticationCode\Eloquent\AuthenticationCodeEloquent;
use App\Domains\AuthenticationCode\Enums\AuthenticationCodePurpose;
use App\Domains\User\Models\User;
use App\Infrastructure\Mail\EmailSender;

readonly class CreateAndSendEmailVerificationCodeAction
{
    public function __construct(
        private AuthenticationCodeEloquent $authenticationCodeEloquent,
    ) {
    }

    public function execute(User $user, AuthenticationCodePurpose $authenticationCodePurpose): string
    {

        $authenticationCode = $this->authenticationCodeEloquent
            ->createEmailVerificationCodeForUser($user, $authenticationCodePurpose);

        EmailSender::sendEmail(
            new ConfirmationCodeMail($user, $authenticationCode),
            $user->email,
        );

        return $authenticationCode->identifier;
    }
}

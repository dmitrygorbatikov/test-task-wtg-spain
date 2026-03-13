<?php

declare(strict_types=1);

namespace App\Application\Auth\Actions\Code;

use App\Application\Auth\Actions\CheckUserHasNotFinishedRegistrationAction;
use App\Application\AuthenticationCode\Actions\CreateAndSendEmailVerificationCodeAction;
use App\Domains\Auth\Exceptions\UserRegistrationAlreadyFinishedException;
use App\Domains\AuthenticationCode\Enums\AuthenticationCodePurpose;
use App\Domains\User\Dto\UserWithCodeIdentifierData;
use App\Domains\User\Eloquent\UserEloquent;
use App\Domains\User\Exceptions\UserFindException;

class ResendEmailRegistrationVerificationAction
{
    public function __construct(
        private readonly UserEloquent $userEloquent,
        private readonly CreateAndSendEmailVerificationCodeAction $createAndSendEmailVerificationCodeAction,
        private readonly CheckUserHasNotFinishedRegistrationAction $checkUserHasNotFinishedRegistrationAction,
    ) {
    }

    /**
     * @throws UserRegistrationAlreadyFinishedException
     * @throws UserFindException
     */
    public function execute(string $email, AuthenticationCodePurpose $purpose): UserWithCodeIdentifierData
    {
        $user = $this->userEloquent->getByEmail($email);

        if ($purpose !== AuthenticationCodePurpose::UserRecoverPassword) {
            $this->checkUserHasNotFinishedRegistrationAction->execute($user);
        }

        $identifier = $this->createAndSendEmailVerificationCodeAction->execute($user, $purpose);

        return new UserWithCodeIdentifierData($user, $identifier);
    }
}

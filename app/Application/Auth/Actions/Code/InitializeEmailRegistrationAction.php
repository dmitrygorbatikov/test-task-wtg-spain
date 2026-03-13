<?php

declare(strict_types=1);

namespace App\Application\Auth\Actions\Code;

use App\Application\Auth\Actions\CheckUserAccountIsAvailableAction;
use App\Application\AuthenticationCode\Actions\CreateAndSendEmailVerificationCodeAction;
use App\Domains\Auth\Exceptions\UserRegistrationUnfinishedException;
use App\Domains\AuthenticationCode\Enums\AuthenticationCodePurpose;
use App\Infrastructure\Services\Slug\GenerateRandomSlugService;
use Random\RandomException;
use App\Domains\User\Dto\{UserData, UserWithCodeIdentifierData};
use App\Domains\User\Eloquent\UserEloquent;
use App\Domains\User\Enums\UserStatusEnum;
use App\Domains\User\Exceptions\{UserEmailAlreadyTakenConflictException, UserFindException};
use App\Domains\User\Models\User;
use Illuminate\Support\Facades\DB;
use App\Infrastructure\Exceptions\{AccountUnavailableException, EntityCreateException};
use Throwable;

readonly class InitializeEmailRegistrationAction
{
    public function __construct(
        private UserEloquent                             $userEloquent,
        private CreateAndSendEmailVerificationCodeAction $createAndSendEmailVerificationCodeAction,
        private CheckUserAccountIsAvailableAction        $checkUserAccountIsAvailableAction,
        private GenerateRandomSlugService                $generateRandomSlugService
    ) {
    }

    /**
     * @throws UserRegistrationUnfinishedException
     * @throws AccountUnavailableException
     * @throws EntityCreateException
     * @throws UserEmailAlreadyTakenConflictException
     * @throws RandomException
     * @throws EntityCreateException
     */
    public function execute(UserData $userData): UserWithCodeIdentifierData
    {
        try {
            $existingUser = $this->userEloquent->getByEmail($userData->email);
        } catch (UserFindException) {
            return $this->registerUser($userData);
        }

        $this->checkUserAccountIsAvailableAction->execute($existingUser);

        throw new UserEmailAlreadyTakenConflictException();
    }

    /**
     * @throws EntityCreateException
     * @throws RandomException
     */
    private function registerUser(UserData $userData): UserWithCodeIdentifierData
    {
        $user = new User();

        $user->email = $userData->email;
        $user->first_name = $userData->firstName;
        $user->last_name = $userData->lastName;
        $user->name = $userData->firstName . ' ' . $userData->lastName;
        $user->slug = $this->generateRandomSlugService->execute($user);
        $user->password = $userData->password;
        $user->status = UserStatusEnum::Created;

        try {
            $userWithCodeData = DB::transaction(function () use ($user): UserWithCodeIdentifierData {
                $this->userEloquent->save($user);
                $codeIdentifier = $this->createAndSendEmailVerificationCodeAction->execute(
                    user: $user,
                    authenticationCodePurpose: AuthenticationCodePurpose::UserFinishEmailRegistrationWithConfirmationCode
                );

                return new UserWithCodeIdentifierData($user, $codeIdentifier);
            });
        } catch (Throwable $e) {
            throw new EntityCreateException(message: 'Error while creating user through email code', previous: $e);
        }

        return $userWithCodeData;
    }
}

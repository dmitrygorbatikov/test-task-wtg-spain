<?php

declare(strict_types=1);

namespace App\Application\Auth\Actions;

use App\Domains\Auth\Exceptions\{InvalidLoginCredentialsException, UserRegistrationUnfinishedException};
use Arr;
use App\Domains\User\Eloquent\UserEloquent;
use App\Domains\User\Enums\UserStatusEnum;
use App\Domains\User\Exceptions\UserFindException;
use App\Domains\User\Models\User;
use Hash;
use App\Infrastructure\Exceptions\AccountUnavailableException;

readonly class LoginUserByEmailAction
{
    public function __construct(
        private UserEloquent                      $userEloquent,
        private CheckUserAccountIsAvailableAction $checkUserAccountIsAvailableAction,
    ) {

    }

    /**
     * @throws AccountUnavailableException
     * @throws UserRegistrationUnfinishedException
     * @throws InvalidLoginCredentialsException
     */
    public function execute(string $email, string $password): User
    {
        try {
            $user = $this->userEloquent->getByEmail($email, ['*'], ...UserStatusEnum::except(UserStatusEnum::Draft));
        } catch (UserFindException) {
            throw new InvalidLoginCredentialsException();
        }

        if (!Hash::check($password, $user->password)) {
            throw new InvalidLoginCredentialsException();
        }

        if ($user->hasStatus(UserStatusEnum::RemovedByUser)) {
            return $user;
        }

        $this->clearInactiveUserTokens($user);

        $this->checkUserAccountIsAvailableAction->execute($user);

        return $user;
    }

    private function clearInactiveUserTokens(User $user): void
    {
        if (!$user->hasStatus(UserStatusEnum::Active, UserStatusEnum::ProfileNotCompleted)) {
            $user->clearTokens();
        }
    }
}

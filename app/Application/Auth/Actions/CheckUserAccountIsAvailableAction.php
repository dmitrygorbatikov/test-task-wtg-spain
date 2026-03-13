<?php

declare(strict_types=1);

namespace App\Application\Auth\Actions;

use App\Domains\Auth\Exceptions\UserRegistrationUnfinishedException;
use App\Domains\User\Enums\UserStatusEnum;
use App\Domains\User\Models\User;
use App\Infrastructure\Exceptions\AccountUnavailableException;

class CheckUserAccountIsAvailableAction
{
    /**
     * @throws AccountUnavailableException
     * @throws UserRegistrationUnfinishedException
     */
    public function execute(User $user): void
    {
        if ($user->hasStatus(UserStatusEnum::Created, UserStatusEnum::Draft)) {
            throw new UserRegistrationUnfinishedException($user);
        }

        if ($user->hasStatus(UserStatusEnum::RemovedByUser, UserStatusEnum::Removed, UserStatusEnum::Blocked)) {
            throw new AccountUnavailableException($user);
        }
    }
}

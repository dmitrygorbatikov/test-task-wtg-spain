<?php

declare(strict_types=1);

namespace App\Application\Auth\Actions;

use App\Domains\Auth\Exceptions\UserRegistrationAlreadyFinishedException;
use App\Domains\User\Enums\UserStatusEnum;
use App\Domains\User\Models\User;

class CheckUserHasNotFinishedRegistrationAction
{
    /**
     * @throws UserRegistrationAlreadyFinishedException
     */
    public function execute(User $user): void
    {
        $statuses = [UserStatusEnum::Created, UserStatusEnum::Draft];

        if (!$user->hasStatus(...$statuses)) {
            throw new UserRegistrationAlreadyFinishedException();
        }

        if ($user->hasStatus(UserStatusEnum::Active)) {
            throw new UserRegistrationAlreadyFinishedException();
        }
    }
}

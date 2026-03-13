<?php

declare(strict_types=1);

namespace App\Domains\User\Enums;

use App\Infrastructure\Interfaces\Enum\{EnumBlockedStatusInterface, EnumInterface, EnumRemovedStatusInterface};
use App\Infrastructure\Traits\Enum\EnumsTrait;

enum UserStatusEnum: string implements EnumBlockedStatusInterface, EnumInterface, EnumRemovedStatusInterface
{
    use EnumsTrait;

    case Draft = 'draft';
    case Created = 'created'; // user created with a first email registration step, but need to be finished to log in
    case ProfileNotCompleted = 'profile_not_completed';
    case Active = 'active';
    case Removed = 'removed';
    case RemovedByUser = 'removed_by_user';
    case Blocked = 'blocked';

    public function isActive(): bool
    {
        return $this === self::Active;
    }

    public function isBlocked(): bool
    {
        return $this === self::Blocked;
    }

    public function isRemoved(): bool
    {
        return $this === self::Removed;
    }

    public function isRemovedByUser(): bool
    {
        return $this === self::RemovedByUser;
    }

    /**
     * @return self[]
     */
    public static function getNotRemovedStatuses(): array
    {
        return self::except(self::getRemovedStatuses());
    }

    /**
     * @return self[]
     */
    public static function getRemovedStatuses(): array
    {
        return [self::Removed, self::RemovedByUser];
    }

    public static function getBlockedStatuses(): array
    {
        return [self::Blocked];
    }

    public static function getPublicStatuses(): array
    {
        return self::except([self::Removed, self::RemovedByUser, self::Draft]);
    }
}

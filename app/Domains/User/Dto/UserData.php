<?php

declare(strict_types=1);

namespace App\Domains\User\Dto;

use App\Domains\User\Enums\UserStatusEnum;
use App\Infrastructure\Dto\{
    Casts\EnumCast,
    Casts\LowercaseCast,
    Data,
};
use Spatie\LaravelData\Attributes\{MapName, WithCast};
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class UserData extends Data
{
    public function __construct(
        #[WithCast(EnumCast::class, UserStatusEnum::class)]
        public readonly ?UserStatusEnum $status,
        public readonly ?string $firstName,
        public readonly ?string $lastName,
        public ?string $slug,
        #[WithCast(LowercaseCast::class)]
        public readonly ?string $email,
        public ?string $password,
    ) {
    }
}

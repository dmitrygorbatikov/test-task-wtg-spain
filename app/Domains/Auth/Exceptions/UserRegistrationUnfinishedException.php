<?php

declare(strict_types=1);

namespace App\Domains\Auth\Exceptions;

use App\Domains\User\Models\User;
use App\Infrastructure\Exceptions\{ForbiddenWithEmailException};
use Throwable;

class UserRegistrationUnfinishedException extends ForbiddenWithEmailException
{
    public function __construct(
        private readonly User $user,
        string $message = '',
        int $code = 500,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getEmail(): string
    {
        return $this->user->email;
    }

    public function getErrorCode(): string
    {
        return 'user.registration.not_finished';
    }
}

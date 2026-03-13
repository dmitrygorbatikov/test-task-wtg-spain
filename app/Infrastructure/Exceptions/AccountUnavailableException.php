<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

use App\Domains\Admin\Models\Admin;
use App\Domains\User\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Throwable;

class AccountUnavailableException extends ForbiddenWithEmailException
{
    public function __construct(
        private readonly Authenticatable $authenticatable,
        string $message = '',
        int $code = 500,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getErrorCode(): string
    {
        return 'account_unavailable';
    }

    public function getEmail(): string
    {
        /** @var Admin|User $authenticatable */
        $authenticatable = $this->authenticatable;

        return $authenticatable->email;
    }
}

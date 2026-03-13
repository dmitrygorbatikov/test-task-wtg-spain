<?php

declare(strict_types=1);

namespace App\Domains\User\Exceptions;

use App\Infrastructure\Exceptions\LogicException;

class UserInvalidDateInFutureException extends LogicException
{
}

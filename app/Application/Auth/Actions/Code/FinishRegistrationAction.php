<?php

declare(strict_types=1);

namespace App\Application\Auth\Actions\Code;

use App\Application\Auth\Actions\CheckUserHasNotFinishedRegistrationAction;
use App\Application\AuthenticationCode\Actions\GetActiveAuthenticationCodeAction;
use DB;
use App\Domains\Auth\Exceptions\UserRegistrationAlreadyFinishedException;
use App\Domains\AuthenticationCode\Eloquent\AuthenticationCodeEloquent;
use App\Domains\AuthenticationCode\Enums\AuthenticationCodePurpose;
use App\Domains\AuthenticationCode\Exceptions\{AuthenticationCodeExpiredException, AuthenticationCodeFindException};
use App\Domains\PersonalAccessToken\Dto\CodeIdentifierWithMailCodeData;
use App\Domains\User\Eloquent\UserEloquent;
use App\Domains\User\Models\User;
use App\Infrastructure\Exceptions\EntityUpdateException;
use Throwable;

class FinishRegistrationAction
{
    public function __construct(
        private readonly CheckUserHasNotFinishedRegistrationAction $checkUserHasNotFinishedRegistrationAction,
        private readonly GetActiveAuthenticationCodeAction $getActiveAuthenticationCodeAction,
        private readonly UserEloquent $userEloquent,
        private readonly AuthenticationCodeEloquent $authenticationCodeEloquent,
    ) {
    }

    /**
     * @throws AuthenticationCodeExpiredException
     * @throws AuthenticationCodeFindException
     * @throws UserRegistrationAlreadyFinishedException
     * @throws EntityUpdateException
     */
    public function execute(CodeIdentifierWithMailCodeData $data, AuthenticationCodePurpose $codePurpose): User
    {
        $authenticationCode = $this->getActiveAuthenticationCodeAction->execute(
            $data->code,
            $data->codeIdentifier,
            $codePurpose,
        );

        /** @var User $user */
        $user = $authenticationCode->authenticatable;

        $this->checkUserHasNotFinishedRegistrationAction->execute($user);

        try {
            DB::transaction(function () use ($user, $codePurpose): void {
                $this->userEloquent->markUserAsVerified($user);
                $this->authenticationCodeEloquent->clearAuthenticationCodes($user, $codePurpose);
            }, config('database.transaction_attempt_count'));
        } catch (Throwable $e) {
            throw new EntityUpdateException('User registration finish failed', previous: $e);
        }

        return $user;
    }
}

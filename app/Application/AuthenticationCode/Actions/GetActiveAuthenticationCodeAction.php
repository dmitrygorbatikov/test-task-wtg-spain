<?php

declare(strict_types=1);

namespace App\Application\AuthenticationCode\Actions;

use App\Domains\AuthenticationCode\Eloquent\AuthenticationCodeEloquent;
use App\Domains\AuthenticationCode\Enums\AuthenticationCodePurpose;
use App\Domains\AuthenticationCode\Exceptions\{AuthenticationCodeExpiredException, AuthenticationCodeFindException};
use App\Domains\AuthenticationCode\Models\AuthenticationCode;

readonly class GetActiveAuthenticationCodeAction
{
    public function __construct(
        private AuthenticationCodeEloquent $authenticationCodeEloquent,
    ) {
    }

    /**
     * @throws AuthenticationCodeExpiredException
     * @throws AuthenticationCodeFindException
     */
    public function execute(
        string $code,
        string $identifier,
        AuthenticationCodePurpose $authenticationCodePurpose
    ): AuthenticationCode {
        $authCode = $this->authenticationCodeEloquent->getByCodeIdentifierAndPurpose(
            $code,
            $identifier,
            $authenticationCodePurpose
        );

        $this->validateAuthCodeNotExpired($authCode);

        return $authCode;
    }

    /**
     * @throws AuthenticationCodeExpiredException
     */
    private function validateAuthCodeNotExpired(AuthenticationCode $authenticationCode): void
    {
        if (!$authenticationCode->isExpired()) {
            return;
        }

        $this->authenticationCodeEloquent
            ->clearAuthenticationCodes($authenticationCode->authenticatable, $authenticationCode->purpose);

        throw new AuthenticationCodeExpiredException();
    }
}

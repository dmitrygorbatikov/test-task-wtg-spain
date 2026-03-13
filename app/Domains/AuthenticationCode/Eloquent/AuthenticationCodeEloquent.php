<?php

declare(strict_types=1);

namespace App\Domains\AuthenticationCode\Eloquent;

use App\Domains\AuthenticationCode\Enums\AuthenticationCodePurpose;
use App\Domains\AuthenticationCode\Exceptions\AuthenticationCodeFindException;
use App\Domains\AuthenticationCode\Models\AuthenticationCode;
use App\Domains\AuthenticationCode\Traits\{HasAuthenticationCodes, HasGeneratedAuthenticationCode};
use App\Infrastructure\Models\Model;
use Str;

class AuthenticationCodeEloquent
{
    use HasGeneratedAuthenticationCode;

    public function save(AuthenticationCode $authenticationCode): AuthenticationCode
    {
        $authenticationCode->save();

        return $authenticationCode;
    }

    /**
     * @throws AuthenticationCodeFindException
     */
    public function getByIdentifier(string $identifier): AuthenticationCode
    {
        return $this->tryGetByIdentifier($identifier) ?? throw new AuthenticationCodeFindException();
    }

    public function tryGetByIdentifier(string $identifier): ?AuthenticationCode
    {
        return AuthenticationCode::firstWhere('identifier', $identifier);
    }

    /**
     * @throws AuthenticationCodeFindException
     */
    public function getByCodeIdentifierAndPurpose(
        string $code,
        string $identifier,
        AuthenticationCodePurpose $authenticationCodePurpose
    ): AuthenticationCode {
        /** @var ?AuthenticationCode $authenticationCode */
        $authenticationCode = AuthenticationCode::where('code', '=', Str::lower($code))
            ->where('identifier', '=', $identifier)
            ->where('purpose', '=', $authenticationCodePurpose)
            ->first();

        return $authenticationCode ?? throw new AuthenticationCodeFindException();
    }

    public function create4DigitCodeForUser(
        HasAuthenticationCodes&Model $user,
        AuthenticationCodePurpose $purpose
    ): AuthenticationCode {
        $code = (string) $this->generate4DigitCodeForUser();

        $this->clearAuthenticationCodes($user, $purpose);

        return $this->createAuthenticationCode($user, $purpose, $code);
    }

    public function createEmailVerificationCodeForUser(
        HasAuthenticationCodes&Model $user,
        AuthenticationCodePurpose $purpose,
    ): AuthenticationCode {
        $code = $this->generateEmailVerificationCode();

        return $this->createAuthenticationCode($user, $purpose, $code);
    }

    public function createUuidBasedCodeForUser(
        HasAuthenticationCodes&Model $user,
        AuthenticationCodePurpose $purpose
    ): AuthenticationCode {
        $this->clearAuthenticationCodes($user, $purpose);

        return $this->createAuthenticationCode($user, $purpose);
    }

    public function createAuthenticationCode(
        HasAuthenticationCodes&Model $user,
        AuthenticationCodePurpose $purpose,
        ?string $code = null,
    ): AuthenticationCode {
        $authenticationCode = new AuthenticationCode();

        $codeIdentifier = Str::uuid()->toString();

        $authenticationCode->code = $code ?? $codeIdentifier;
        $authenticationCode->identifier = $codeIdentifier;
        $authenticationCode->purpose = $purpose;
        $authenticationCode->authenticatable_id = $user->getKey();
        $authenticationCode->authenticatable_type = $user->getMorphClass();

        return $this->save($authenticationCode);
    }

    public function clearAuthenticationCodes(
        HasAuthenticationCodes $authenticatable,
        AuthenticationCodePurpose $purpose
    ): void {
        $authenticatable->authenticationCodes()
            ->where('purpose', $purpose)
            ->delete();
    }
}

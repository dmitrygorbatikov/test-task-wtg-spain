<?php

declare(strict_types=1);

namespace App\UI\Api\Http\Controllers\Auth\Code;

use App\UI\Api\Http\Resources\Auth\InitializeEmailRegistrationResource;
use App\Application\Auth\Actions\Code\ResendEmailRegistrationVerificationAction;
use App\Domains\Auth\Exceptions\UserRegistrationAlreadyFinishedException;
use App\Domains\User\Exceptions\UserFindException;
use Illuminate\Http\JsonResponse;
use App\Infrastructure\Exceptions\ValidationException;
use App\Infrastructure\Http\Controllers\Controller;
use App\UI\Api\Http\Requests\Auth\ResendEmailRegistrationVerificationRequest;

class ResendRegistrationVerificationController extends Controller
{
    /**
     * @throws ValidationException
     * @throws UserRegistrationAlreadyFinishedException
     */
    public function __invoke(
        ResendEmailRegistrationVerificationRequest $request,
        ResendEmailRegistrationVerificationAction $resendEmailRegistrationVerificationAction
    ): JsonResponse {
        try {
            $userWithCodeIdentifierData = $resendEmailRegistrationVerificationAction->execute(
                $request->getEmail(),
                $request->getCodePurpose()
            );

            return response()->json(
                new InitializeEmailRegistrationResource(
                    $userWithCodeIdentifierData->user,
                    $userWithCodeIdentifierData->codeIdentifier
                ),
            );
        } catch (UserFindException) {
            throw ValidationException::withKey('email');
        }
    }
}

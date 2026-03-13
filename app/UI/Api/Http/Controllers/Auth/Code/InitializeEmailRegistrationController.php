<?php

declare(strict_types=1);

namespace App\UI\Api\Http\Controllers\Auth\Code;

use App\UI\Api\Http\Requests\Auth\InitializeEmailRegistrationRequest;
use App\UI\Api\Http\Resources\Auth\InitializeEmailRegistrationResource;
use App\Application\Auth\Actions\Code\InitializeEmailRegistrationAction;
use App\Domains\Auth\Exceptions\UserRegistrationUnfinishedException;
use App\Domains\User\Exceptions\UserEmailAlreadyTakenConflictException;
use Illuminate\Http\JsonResponse;
use App\Infrastructure\Exceptions\{AccountUnavailableException, EntityCreateException};
use App\Infrastructure\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class InitializeEmailRegistrationController extends Controller
{
    /**
     * @throws UserRegistrationUnfinishedException
     * @throws AccountUnavailableException
     * @throws EntityCreateException
     * @throws UserEmailAlreadyTakenConflictException
     */
    public function __invoke(
        InitializeEmailRegistrationRequest $request,
        InitializeEmailRegistrationAction $initializeEmailRegistrationAction,
    ): JsonResponse {
        $userWithCodeIdentifierData = $initializeEmailRegistrationAction->execute($request->getData());

        return response()->json(
            new InitializeEmailRegistrationResource(
                $userWithCodeIdentifierData->user,
                $userWithCodeIdentifierData->codeIdentifier
            ),
            Response::HTTP_CREATED
        );
    }
}

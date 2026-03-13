<?php

declare(strict_types=1);

namespace App\UI\Api\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Validation\ValidationException;
use App\Infrastructure\Enums\EnvironmentEnum;
use App\Infrastructure\Exceptions\Handler as InfrastructureHandler;
use App\Infrastructure\Exceptions\{ConflictException,
    EntityFindException,
    ExternalServiceException,
    ForbiddenException,
    ForbiddenWithAdditionalDataException,
    ForbiddenWithEmailException,
    UnauthorizedException
};
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\{MethodNotAllowedHttpException, NotFoundHttpException};
use Throwable;

final class Handler extends InfrastructureHandler
{
    protected $dontReport = [
        AuthenticationException::class,
        ConflictException::class,
        EntityFindException::class,
        ExternalServiceException::class,
        ForbiddenException::class,
        InvalidSignatureException::class,
        NotFoundHttpException::class,
        MethodNotAllowedHttpException::class,
        ThrottleRequestsException::class,
        ValidationException::class,
        UnauthorizedException::class,
    ];

    public function render(mixed $request, Throwable $e): Response
    {
        if ($e instanceof InvalidSignatureException) {
            return response()->json([
                'error' => __('api::errors.invalid_signature.code'),
                'message' => __('api::errors.invalid_signature.message'),
            ], Response::HTTP_NOT_FOUND);
        }

        if ($e instanceof AuthenticationException) {
            return response()->json([
                'error' => __('api::errors.invalid_session_token.code'),
                'message' => __('api::errors.invalid_session_token.message'),
            ], Response::HTTP_UNAUTHORIZED);
        }

        if ($e instanceof UnauthorizedException) {
            return response()->json([
                'error' => __('api::errors.' . $e->getErrorCode() . '.code'),
                'message' => __('api::errors.' . $e->getErrorCode() . '.message'),
            ], Response::HTTP_UNAUTHORIZED);
        }

        if ($e instanceof AuthorizationException) {
            if (EnvironmentEnum::isLocal() || EnvironmentEnum::isTesting()) {
                throw new RuntimeException(sprintf(
                    'Check %s::getFeaturesForGate() permission list',
//                    SubscriptionFeatureEnum::class
                'unknown subscription'
                ));
            }

            return response()->json([
                'error' => __('api::errors.permission_access_forbidden.code'),
                'message' => __('api::errors.permission_access_forbidden.message'),
            ], Response::HTTP_FORBIDDEN);
        }

        if ($e instanceof ForbiddenWithEmailException) {
            return response()->json([
                'error' => __('api::errors.' . $e->getErrorCode() . '.code'),
                'message' => __('api::errors.' . $e->getErrorCode() . '.message'),
                'email' => $e->getEmail(),
            ], Response::HTTP_FORBIDDEN);
        }

        if ($e instanceof ForbiddenWithAdditionalDataException) {
            return response()->json([
                'error' => __("api::errors.{$e->getErrorCode()}.code"),
                'message' => __("api::errors.{$e->getErrorCode()}.message", $e->getData()),
            ], Response::HTTP_FORBIDDEN);
        }

        if ($e instanceof ForbiddenException) {
            return response()->json([
                'error' => __('api::errors.' . $e->getErrorCode() . '.code'),
                'message' => __('api::errors.' . $e->getErrorCode() . '.message'),
            ], Response::HTTP_FORBIDDEN);
        }

        if ($e instanceof EntityFindException) {
            return response()->json([
                'error' => __('api::errors.entity_not_found.code', [
                    'entity' => $e->getCodeName(),
                ]),
                'message' => __('api::errors.entity_not_found.message', [
                    'entity' => $e->getEntityName(),
                    'field' => $e->getEntityField(),
                ]),
            ], Response::HTTP_NOT_FOUND);
        }

        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'error' => __('api::errors.method_not_found.code'),
                'message' => __('api::errors.method_not_found.message'),
            ], Response::HTTP_NOT_FOUND);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'error' => __('api::errors.method_not_allowed.code'),
                'message' => __('api::errors.method_not_allowed.message'),
            ], Response::HTTP_METHOD_NOT_ALLOWED);
        }

        if ($e instanceof ConflictException) {
            return response()->json([
                'error' => __('api::errors.' . $e->getErrorKey() . '.code', [
                    'field' => $e->getEntityField(),
                    'entityName' => $e->getEntityName(),
                ]),
                'message' => __('api::errors.' . $e->getErrorKey() . '.message', [
                    'field' => $e->getEntityField(),
                    'entityName' => $e->getEntityName(),
                    'errorMessage' => $e->getErrorMessage(),
                    ...$e->getData(),
                ]),
            ], Response::HTTP_CONFLICT);
        }

        if ($e instanceof ValidationException) {
            return response()->json([
                'error' => __('api::errors.validation_error.code'),
                'message' => __('api::errors.validation_error.message'),
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($e instanceof ThrottleRequestsException) {
            return response()->json([
                'error' => __('api::errors.too_many_requests.code'),
                'message' => $e->getMessage(),
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }

        if ($e instanceof ExternalServiceException) {
            return $this->badGateway($e);
        }

        return parent::render($request, $e);
    }

    protected function badGateway(ExternalServiceException $e): Response
    {
        return response()->json([
            'error' => __("api::errors.external_services.{$e->getErrorKey()}.code"),
            'message' => __("api::errors.external_services.{$e->getErrorKey()}.message"),
        ], Response::HTTP_BAD_GATEWAY);
    }
}

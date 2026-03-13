<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use Illuminate\Contracts\Pagination\{CursorPaginator, LengthAwarePaginator, Paginator};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\{JsonResource, ResourceCollection};
use Illuminate\Pagination\{AbstractCursorPaginator, AbstractPaginator};
use Illuminate\Routing\Controller as BaseController;
use RuntimeException;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;

    protected function buildPaginatedResponse(
        string $resourceClass,
        AbstractPaginator|LengthAwarePaginator|Paginator|AbstractCursorPaginator|CursorPaginator $paginator,
        ?string $name = null,
        ?array $params = null,
        bool $withCloudflareCacheControl = false
    ): JsonResponse {
        if (
            !is_subclass_of($resourceClass, JsonResource::class)
            && !is_subclass_of($resourceClass, ResourceCollection::class)
        ) {
            throw new RuntimeException('Resource class must be a subclass of ' . JsonResource::class);
        }

        $headers = match (true) {
            $paginator instanceof LengthAwarePaginator => [
                'Access-Control-Expose-Headers' => 'Content-Range',
                'Content-Range' => $this->generateContentRangeFromPaginator($paginator, $name),
            ],
            $paginator instanceof AbstractCursorPaginator => [
                'X-Cursor-Next' => $paginator->nextCursor()?->encode(),
                'X-Cursor-Prev' => $paginator->previousCursor()?->encode(),
            ],
            default => []
        };

        $response = response()->json(
            is_subclass_of($resourceClass, ResourceCollection::class)
                ? new $resourceClass($paginator, $params)
                : $resourceClass::collection($paginator)
        )
            ->withHeaders($headers);

        return $withCloudflareCacheControl ? $this->withCloudflareCacheControl($response) : $response;
    }

    protected function generateContentRangeFromPaginator(LengthAwarePaginator $paginator, string $key): string
    {
        $firstItem = ($paginator->firstItem() - 1 < 0) ? 0 : ($paginator->firstItem() - 1);
        $lastItem = ($paginator->lastItem() - 1 < 0) ? 0 : ($paginator->lastItem() - 1);

        return "{$key} {$firstItem}-{$lastItem}/{$paginator->total()}";
    }

    protected function generateContentRangeFromPageAndPerPage(int $page, int $perPage, int $total, string $key): string
    {
        $firstItem = ($page * $perPage) - $perPage;
        $lastItem = (min($perPage, $total) * $page) - 1;

        return "{$key} {$firstItem}-{$lastItem}/{$total}";
    }

    /**
     * If you use this method in a controller, you need to add in the ClearCFCacheCommand
     */
    protected function withCloudflareCacheControl(JsonResponse $response): JsonResponse
    {
        return $response->withHeaders([
            'Cache-Control' => 'public, max-age=' . config('cache.cloudflare.public.ttl'),
        ]);
    }
}

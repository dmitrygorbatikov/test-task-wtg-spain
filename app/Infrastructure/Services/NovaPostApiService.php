<?php


namespace App\Infrastructure\Services;

use Illuminate\Support\Arr;
use NovaDigital\NovaPost\NovaPostApiFactory;
use NovaDigital\NovaPost\Resources\Division;
use Psr\Http\Client\ClientExceptionInterface;
use RuntimeException;

class NovaPostApiService
{
    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('nova-post.api_key');
    }

    public function fetchDivisions(string $textSearch, string $divisionCategory): array
    {
        try {
            $novaPostApi = (new NovaPostApiFactory())($this->apiKey);

            $searchParams = [
                'textSearch' => $textSearch,
                'countryCodes[]' => 'UA',
                'divisionCategories' => [$divisionCategory]
            ];

            $divisions = $novaPostApi->divisions()->get($searchParams);

            return $this->formatDivisions($divisions);
        } catch (ClientExceptionInterface $e) {
            throw new RuntimeException("ClientExceptionInterface Error: " . $e->getMessage());
        }
    }

    private function formatDivisions(array $divisions): array
    {
        return collect(Arr::get($divisions, 'items', []))
            ->map(fn(array $item): array => [
                'externalId' => $item['externalId'] ?? null,
                'name'       => $item['name']       ?? null,
                'shortName'  => $item['shortName']  ?? null,
                'address'    => $item['address']    ?? null,
            ])
            ->filter()
            ->values()
            ->all();
    }
}

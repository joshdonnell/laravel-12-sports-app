<?php

declare(strict_types=1);

namespace App\Services\Countries;

use App\Exceptions\CountryServiceException;
use Illuminate\Support\Facades\Log;

final readonly class GetCountries
{
    /**
     * @return array<int, array<string, mixed>>
     *
     * @throws CountryServiceException
     */
    public function getCountries(): array
    {
        $apiUrl = config('services.countries.api_url');
        $response = \Illuminate\Support\Facades\Http::timeout(10)->get($apiUrl);

        if ($response->failed()) {
            Log::error('Countries API responded with error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new CountryServiceException('Countries API responded with error: '.$response->status());
        }

        if (empty($response->json()) || ! is_array($response->json())) {
            Log::warning('Countries API responded with invalid data', [
                'body' => $response->json(),
            ]);

            return [];
        }

        return $this->mapCountries($response->json());
    }

    /**
     * @param  array<int, array<string, mixed>>  $countries
     * @return array<int, array<string, mixed>>
     */
    private function mapCountries(array $countries): array
    {
        return collect($countries)->map(fn (array $country): array => [
            'name' => $country['name']['common'],
            'code' => $country['cca2'],
            'flag' => $country['flags']['png'],
        ])->all();
    }
}

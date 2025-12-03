<?php

declare(strict_types=1);

use App\Exceptions\CountryServiceException;
use App\Services\Countries\GetCountries;
use Illuminate\Support\Facades\Http;

it('can get countries via the 3rd party API', function (): void {
    Http::fake([
        config('services.countries.api_url') => Http::response(file_get_contents(base_path('tests/Fixtures/countries.json')), 200),
    ]);

    $service = resolve(GetCountries::class);
    $countries = $service->getCountries();
    expect($countries)->toBeArray()
        ->and($countries)->toHaveCount(2);
});

it('will throw on a network error', function (): void {
    Http::fake([
        config('services.countries.api_url') => Http::response('Error', 500),
    ]);

    $service = resolve(GetCountries::class);
    $service->getCountries();
})->throws(CountryServiceException::class);

it('will return an empty array when no data is returned', function (): void {
    Http::fake([
        config('services.countries.api_url') => Http::response([], 200),
    ]);

    $service = resolve(GetCountries::class);
    $countries = $service->getCountries();

    expect($countries)->toBeArray()
        ->and($countries)->toHaveCount(0)
        ->and($countries)->toBeCalled;
});

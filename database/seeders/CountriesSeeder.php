<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Exceptions\CountryServiceException;
use App\Models\Country;
use App\Services\Countries\GetCountries;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

final class CountriesSeeder extends Seeder
{
    public function run(): void
    {
        $service = app(GetCountries::class);

        try {
            $countries = $service->getCountries();
        } catch (CountryServiceException $e) {
            Log::info('Failed to get countries from API, skipping seeding', ['error' => $e->getMessage()]);

            return;
        }

        foreach ($countries as $country) {
            Country::query()->updateOrCreate(['code' => $country['code']], $country);
        }
    }
}

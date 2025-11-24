<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Genders;
use App\Models\Country;
use App\Models\Player;
use App\Models\Sport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Player>
 */
final class PlayerFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'known_as' => fake()->name(),
            'match_name' => fake()->name(),
            'gender' => fake()->randomElement(array_column(Genders::cases(), 'value')),
            'date_of_birth' => fake()->date(),
            'country_id' => Country::factory()->create(),
            'height' => fake()->numberBetween(150, 200).'cm',
            'weight' => fake()->numberBetween(50, 100).'kg',
            'profile_picture' => fake()->imageUrl(),
            'sport_id' => Sport::factory()->create(),
        ];
    }
}

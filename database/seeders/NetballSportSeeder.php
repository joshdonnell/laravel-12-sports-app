<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Client;
use App\Models\Sport;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class NetballSportSeeder extends Seeder
{
    public function run(): void
    {
        try {
            DB::transaction(function (): void {
                $netball = Sport::query()->create([
                    'name' => 'Netball',
                ]);

                $exampleClient = Client::query()->create([
                    'sport_id' => $netball->id,
                    'name' => 'Example',
                ]);

                if (config('app.env') !== 'production') {
                    $user = User::query()->create([
                        'name' => 'Example',
                        'email' => 'admin@example.com',
                        'password' => bcrypt('password'),
                        'email_verified_at' => now(),
                        'sport_id' => $netball->id,
                    ]);

                    $user->assignRole(Role::SuperAdmin);

                    $user->clients()->attach($exampleClient);
                }

                $this->call(NetballEventTypeSeeder::class, parameters: [
                    'sport' => $netball,
                ]);
                $this->call(NetballRuleSeeder::class, parameters: [
                    'sport' => $netball,
                ]);

            });
        } catch (Throwable $exception) {
            Log::error('There was an error running the Netball seeder.', ['exception' => $exception]);
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Actions\Client;

use App\Models\Client;
use Illuminate\Support\Facades\DB;

final readonly class UpdateClient
{
    public function __construct(private DetachUsersMismatchingSport $detachUsersMismatchingSport) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(Client $client, array $attributes): void
    {
        $sportChanged = array_key_exists('sport_id', $attributes) && $client->sport_id !== $attributes['sport_id'];

        DB::transaction(function () use ($client, $attributes, $sportChanged): void {
            $client->update($attributes);

            if ($sportChanged) {
                $this->detachUsersMismatchingSport->handle($client);
            }
        });
    }
}

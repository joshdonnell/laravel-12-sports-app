<?php

declare(strict_types=1);

use App\Data\Client\ClientData;
use App\Models\Client;
use App\Models\Sport;
use Spatie\LaravelData\Exceptions\CannotCreateData;

it('can be created with all required fields', function (): void {
    $sport = Sport::factory()->create(['name' => 'Basketball']);

    $clientData = ClientData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        'name' => 'Client Name',
        'sport_id' => $sport->id,
        'sport' => $sport,
    ]);

    expect($clientData)->toBeInstanceOf(ClientData::class)
        ->and($clientData->uuid)->toBe('123e4567-e89b-12d3-a456-426614174000')
        ->and($clientData->name)->toBe('Client Name')
        ->and($clientData->sport_id)->toBe($sport->id)
        ->and($clientData->sport_name)->toBe('Basketball');
});

it('errors when uuid is not provided', function (): void {
    $sport = Sport::factory()->create();

    expect(fn (): ClientData => ClientData::from([
        'name' => 'Client Name',
        'sport_id' => $sport->id,
        'sport' => $sport,
    ]))->toThrow(CannotCreateData::class);
});

it('errors when name is not provided', function (): void {
    $sport = Sport::factory()->create();

    expect(fn (): ClientData => ClientData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        'sport_id' => $sport->id,
        'sport' => $sport,
    ]))->toThrow(CannotCreateData::class);
});

it('errors when sport_id is not provided', function (): void {
    $sport = Sport::factory()->create();

    expect(fn (): ClientData => ClientData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        'name' => 'Client Name',
        'sport' => $sport,
    ]))->toThrow(CannotCreateData::class);
});

it('errors when sport is not provided', function (): void {
    $sport = Sport::factory()->create();

    expect(fn (): ClientData => ClientData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        'name' => 'Client Name',
        'sport_id' => $sport->id,
    ]))->toThrow(CannotCreateData::class);
});

it('can be created from a Client model', function (): void {
    $sport = Sport::factory()->create(['name' => 'Soccer']);
    $client = Client::factory()->create([
        'name' => 'Test Client',
        'sport_id' => $sport->id,
    ]);
    $client->load('sport');

    $clientData = ClientData::from($client);

    expect($clientData)->toBeInstanceOf(ClientData::class)
        ->and($clientData->uuid)->toBe($client->uuid)
        ->and($clientData->name)->toBe('Test Client')
        ->and($clientData->sport_id)->toBe($sport->id)
        ->and($clientData->sport_name)->toBe('Soccer');
});

it('computes sport_name from sport relationship', function (): void {
    $sport = Sport::factory()->create(['name' => 'Netball']);

    $clientData = ClientData::from([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        'name' => 'Netball Client',
        'sport_id' => $sport->id,
        'sport' => $sport,
    ]);

    expect($clientData->sport_name)->toBe('Netball');
});

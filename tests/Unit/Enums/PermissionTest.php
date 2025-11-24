<?php

declare(strict_types=1);

use App\Enums\Permission;

it('has all expected cases', function (): void {
    expect(Permission::cases())->toHaveCount(52);
});

it('has correct string values for client permissions', function (): void {
    expect(Permission::LIST_CLIENTS->value)->toBe('list-clients');
    expect(Permission::CREATE_CLIENT->value)->toBe('create-client');
    expect(Permission::UPDATE_CLIENT->value)->toBe('update-client');
    expect(Permission::DELETE_CLIENT->value)->toBe('delete-client');
});

it('has correct string values for club permissions', function (): void {
    expect(Permission::LIST_CLUBS->value)->toBe('list-clubs');
    expect(Permission::CREATE_CLUB->value)->toBe('create-club');
    expect(Permission::UPDATE_CLUB->value)->toBe('update-club');
    expect(Permission::DELETE_CLUB->value)->toBe('delete-club');
});

it('has correct string values for fixture permissions', function (): void {
    expect(Permission::LIST_FIXTURES->value)->toBe('list-fixtures');
    expect(Permission::CREATE_FIXTURE->value)->toBe('create-fixture');
    expect(Permission::UPDATE_FIXTURE->value)->toBe('update-fixture');
    expect(Permission::DELETE_FIXTURE->value)->toBe('delete-fixture');
});

it('has correct string values for player permissions', function (): void {
    expect(Permission::LIST_PLAYERS->value)->toBe('list-players');
    expect(Permission::CREATE_PLAYER->value)->toBe('create-player');
    expect(Permission::UPDATE_PLAYER->value)->toBe('update-player');
    expect(Permission::DELETE_PLAYER->value)->toBe('delete-player');
});

it('has correct string values for position permissions', function (): void {
    expect(Permission::LIST_POSITIONS->value)->toBe('list-positions');
    expect(Permission::CREATE_POSITION->value)->toBe('create-position');
    expect(Permission::UPDATE_POSITION->value)->toBe('update-position');
    expect(Permission::DELETE_POSITION->value)->toBe('delete-position');
});

it('has correct string values for round permissions', function (): void {
    expect(Permission::LIST_ROUNDS->value)->toBe('list-rounds');
    expect(Permission::CREATE_ROUND->value)->toBe('create-round');
    expect(Permission::UPDATE_ROUND->value)->toBe('update-round');
});

it('has correct string values for ruleset permissions', function (): void {
    expect(Permission::LIST_RULESETS->value)->toBe('list-rulesets');
    expect(Permission::CREATE_RULESET->value)->toBe('create-ruleset');
    expect(Permission::UPDATE_RULESET->value)->toBe('update-ruleset');
    expect(Permission::DELETE_RULESET->value)->toBe('delete-ruleset');
});

it('has correct string values for scoring permissions', function (): void {
    expect(Permission::LIST_SCORING->value)->toBe('list-scoring');
});

it('has correct string values for season permissions', function (): void {
    expect(Permission::LIST_SEASONS->value)->toBe('list-seasons');
    expect(Permission::CREATE_SEASON->value)->toBe('create-season');
    expect(Permission::UPDATE_SEASON->value)->toBe('update-season');
});

it('has correct string values for sport permissions', function (): void {
    expect(Permission::LIST_SPORTS->value)->toBe('list-sports');
    expect(Permission::CREATE_SPORT->value)->toBe('create-sport');
    expect(Permission::UPDATE_SPORT->value)->toBe('update-sport');
});

it('has correct string values for standings permissions', function (): void {
    expect(Permission::LIST_STANDINGS->value)->toBe('list-standings');
});

it('has correct string values for team permissions', function (): void {
    expect(Permission::LIST_TEAMS->value)->toBe('list-teams');
    expect(Permission::CREATE_TEAM->value)->toBe('create-team');
    expect(Permission::UPDATE_TEAM->value)->toBe('update-team');
    expect(Permission::DELETE_TEAM->value)->toBe('delete-team');
});

it('has correct string values for tournament permissions', function (): void {
    expect(Permission::LIST_TOURNAMENTS->value)->toBe('list-tournaments');
    expect(Permission::CREATE_TOURNAMENT->value)->toBe('create-tournament');
    expect(Permission::UPDATE_TOURNAMENT->value)->toBe('update-tournament');
    expect(Permission::DELETE_TOURNAMENT->value)->toBe('delete-tournament');
});

it('has correct string values for user permissions', function (): void {
    expect(Permission::LIST_USERS->value)->toBe('list-users');
    expect(Permission::CREATE_USER->value)->toBe('create-user');
    expect(Permission::UPDATE_USER->value)->toBe('update-user');
    expect(Permission::DELETE_USER->value)->toBe('delete-user');
});

it('has correct string values for venue permissions', function (): void {
    expect(Permission::LIST_VENUES->value)->toBe('list-venues');
    expect(Permission::CREATE_VENUE->value)->toBe('create-venue');
    expect(Permission::UPDATE_VENUE->value)->toBe('update-venue');
    expect(Permission::DELETE_VENUE->value)->toBe('delete-venue');
});

it('can be created from string value', function (): void {
    expect(Permission::from('list-users'))->toBe(Permission::LIST_USERS);
    expect(Permission::from('create-client'))->toBe(Permission::CREATE_CLIENT);
    expect(Permission::from('update-season'))->toBe(Permission::UPDATE_SEASON);
    expect(Permission::from('list-standings'))->toBe(Permission::LIST_STANDINGS);
});

it('throws exception when creating from invalid string value', function (): void {
    expect(fn () => Permission::from('invalid-permission'))->toThrow(ValueError::class);
});

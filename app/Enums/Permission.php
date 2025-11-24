<?php

declare(strict_types=1);

namespace App\Enums;

enum Permission: string
{
    // Clients
    case LIST_CLIENTS = 'list-clients';
    case CREATE_CLIENT = 'create-client';
    case UPDATE_CLIENT = 'update-client';
    case DELETE_CLIENT = 'delete-client';

    // Clubs
    case LIST_CLUBS = 'list-clubs';
    case CREATE_CLUB = 'create-club';
    case UPDATE_CLUB = 'update-club';
    case DELETE_CLUB = 'delete-club';

    // Fixtures
    case LIST_FIXTURES = 'list-fixtures';
    case CREATE_FIXTURE = 'create-fixture';
    case UPDATE_FIXTURE = 'update-fixture';
    case DELETE_FIXTURE = 'delete-fixture';

    // Players
    case LIST_PLAYERS = 'list-players';
    case CREATE_PLAYER = 'create-player';
    case UPDATE_PLAYER = 'update-player';
    case DELETE_PLAYER = 'delete-player';

    // Positions
    case LIST_POSITIONS = 'list-positions';
    case CREATE_POSITION = 'create-position';
    case UPDATE_POSITION = 'update-position';
    case DELETE_POSITION = 'delete-position';

    // Rounds
    case LIST_ROUNDS = 'list-rounds';
    case CREATE_ROUND = 'create-round';
    case UPDATE_ROUND = 'update-round';

    // Rulesets
    case LIST_RULESETS = 'list-rulesets';
    case CREATE_RULESET = 'create-ruleset';
    case UPDATE_RULESET = 'update-ruleset';
    case DELETE_RULESET = 'delete-ruleset';

    // Scoring
    case LIST_SCORING = 'list-scoring';

    // Seasons
    case LIST_SEASONS = 'list-seasons';
    case CREATE_SEASON = 'create-season';
    case UPDATE_SEASON = 'update-season';

    // Sports
    case LIST_SPORTS = 'list-sports';
    case CREATE_SPORT = 'create-sport';
    case UPDATE_SPORT = 'update-sport';

    // Standings
    case LIST_STANDINGS = 'list-standings';

    // Stats
    case LIST_STATS = 'list-stats';

    // Teams
    case LIST_TEAMS = 'list-teams';
    case CREATE_TEAM = 'create-team';
    case UPDATE_TEAM = 'update-team';
    case DELETE_TEAM = 'delete-team';

    // Tournaments
    case LIST_TOURNAMENTS = 'list-tournaments';
    case CREATE_TOURNAMENT = 'create-tournament';
    case UPDATE_TOURNAMENT = 'update-tournament';
    case DELETE_TOURNAMENT = 'delete-tournament';

    // Users
    case LIST_USERS = 'list-users';
    case CREATE_USER = 'create-user';
    case UPDATE_USER = 'update-user';
    case DELETE_USER = 'delete-user';

    // Venues
    case LIST_VENUES = 'list-venues';
    case CREATE_VENUE = 'create-venue';
    case UPDATE_VENUE = 'update-venue';
    case DELETE_VENUE = 'delete-venue';

}

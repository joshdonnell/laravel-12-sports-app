<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\FixtureStatus;
use App\Traits\HasUuidTrait;
use Carbon\CarbonInterface;
use Database\Factories\FixtureFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Znck\Eloquent\Relations\BelongsToThrough;

/**
 * @property-read int $id
 * @property-read string $uuid
 * @property-read string $name
 * @property-read string|null $description
 * @property-read int|null $week
 * @property-read CarbonInterface $date
 * @property-read FixtureStatus $status
 * @property-read int $home_team
 * @property-read int $away_team
 * @property-read int|null $home_team_score
 * @property-read int|null $away_team_score
 * @property-read int|null $home_team_bonus_score
 * @property-read int|null $away_team_bonus_score
 * @property-read int $venue_id
 * @property-read int $tournament_id
 * @property-read int|null $round_id
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read CarbonInterface|null $deleted_at
 */
final class Fixture extends Model
{
    /** @use HasFactory<FixtureFactory> */
    use HasFactory, HasUuidTrait, SoftDeletes, \Znck\Eloquent\Traits\BelongsToThrough;

    /**
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'id' => 'integer',
            'uuid' => 'string',
            'name' => 'string',
            'description' => 'string',
            'week' => 'integer',
            'date' => 'datetime',
            'status' => FixtureStatus::class,
            'home_team' => 'integer',
            'away_team' => 'integer',
            'home_team_score' => 'integer',
            'away_team_score' => 'integer',
            'home_team_bonus_score' => 'integer',
            'away_team_bonus_score' => 'integer',
            'venue_id' => 'integer',
            'tournament_id' => 'integer',
            'round_id' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Team, $this>
     */
    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'home_team');
    }

    /**
     * @return BelongsTo<Team, $this>
     */
    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'away_team');
    }

    /**
     * @return BelongsTo<Venue, $this>
     */
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * @return BelongsTo<Tournament, $this>
     */
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * @return BelongsTo<Round, $this>
     */
    public function round(): BelongsTo
    {
        return $this->belongsTo(Round::class);
    }

    /**
     * @return BelongsToMany<Client, $this>
     */
    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(Client::class, 'client_tournament', 'tournament_id', null, 'tournament_id');
    }

    /**
     * @return BelongsToThrough<Sport, $this>
     */
    public function sport(): BelongsToThrough
    {
        return $this->belongsToThrough(Sport::class, Tournament::class);
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Database\Factories\StandingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property-read int $position
 * @property-read int $points
 * @property-read int $bonus_points
 * @property-read int $played
 * @property-read int $won
 * @property-read int $drawn
 * @property-read int $lost
 * @property-read int $score_for
 * @property-read int $score_against
 * @property-read int $score_difference
 * @property-read int $season_id
 * @property-read int $tournament_id
 * @property-read int $team_id
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 */
final class Standing extends Model
{
    /** @use HasFactory<StandingFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'id' => 'integer',
            'position' => 'integer',
            'points' => 'integer',
            'bonus_points' => 'integer',
            'played' => 'integer',
            'won' => 'integer',
            'drawn' => 'integer',
            'lost' => 'integer',
            'score_for' => 'integer',
            'score_against' => 'integer',
            'score_difference' => 'integer',
            'season_id' => 'integer',
            'tournament_id' => 'integer',
            'team_id' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Tournament, $this>
     */
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * @return BelongsTo<Season, $this>
     */
    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    /**
     * @return BelongsTo<Team, $this>
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}

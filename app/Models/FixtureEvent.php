<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Database\Factories\FixtureEventFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property-read int $event_time
 * @property-read int|null $event_period
 * @property-read array<string, mixed>|null $event_metadata
 * @property-read int $fixture_id
 * @property-read int $team_id
 * @property-read int $player_id
 * @property-read int $fixture_event_type_id
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 */
final class FixtureEvent extends Model
{
    /** @use HasFactory<FixtureEventFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'id' => 'integer',
            'event_time' => 'integer',
            'event_period' => 'integer',
            'event_metadata' => 'array',
            'fixture_id' => 'integer',
            'team_id' => 'integer',
            'player_id' => 'integer',
            'fixture_event_type_id' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Fixture, $this>
     */
    public function fixture(): BelongsTo
    {
        return $this->belongsTo(Fixture::class);
    }

    /**
     * @return BelongsTo<Player, $this>
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    /**
     * @return BelongsTo<Team, $this>
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * @return BelongsTo<FixtureEventType, $this>
     */
    public function eventType(): BelongsTo
    {
        return $this->belongsTo(FixtureEventType::class, 'fixture_event_type_id');
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Genders;
use App\Models\Scopes\SportScope;
use App\Traits\HasUuidTrait;
use Carbon\CarbonInterface;
use Database\Factories\PlayerFactory;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read int $id
 * @property-read string $uuid
 * @property-read string $first_name
 * @property-read string|null $last_name
 * @property-read string|null $known_as
 * @property-read string|null $match_name
 * @property-read Genders|null $gender
 * @property-read CarbonInterface|null $date_of_birth
 * @property-read int $country_id
 * @property-read string|null $height
 * @property-read string|null $weight
 * @property-read string|null $profile_picture
 * @property-read int $sport_id
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read CarbonInterface|null $deleted_at
 */
#[ScopedBy([SportScope::class])]
final class Player extends Model
{
    /** @use HasFactory<PlayerFactory> */
    use HasFactory, HasUuidTrait, SoftDeletes;

    /**
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'id' => 'integer',
            'uuid' => 'string',
            'first_name' => 'string',
            'last_name' => 'string',
            'known_as' => 'string',
            'match_name' => 'string',
            'gender' => Genders::class,
            'date_of_birth' => 'datetime',
            'country_id' => 'integer',
            'height' => 'string',
            'weight' => 'string',
            'profile_picture' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Country, $this>
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @return BelongsToMany<Position, $this>
     */
    public function positions(): BelongsToMany
    {
        return $this->belongsToMany(Position::class);
    }

    /**
     * @return BelongsToMany<Team, $this>
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }

    /**
     * @return BelongsTo<Sport, $this>
     */
    public function sport(): BelongsTo
    {
        return $this->belongsTo(Sport::class);
    }

    /**
     * @return MorphMany<Stat, $this>
     */
    public function stats(): MorphMany
    {
        return $this->morphMany(Stat::class, 'statable');
    }
}

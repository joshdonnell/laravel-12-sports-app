<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Scopes\SportScope;
use App\Traits\HasUuidTrait;
use Carbon\CarbonInterface;
use Database\Factories\ClubFactory;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * @property-read int $id
 * @property-read string $uuid
 * @property-read string $name
 * @property-read string|null $known_as
 * @property-read string|null $official_name
 * @property-read string|null $code
 * @property-read string|null $logo
 * @property-read string|null $bio
 * @property-read int $sport_id
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read CarbonInterface|null $deleted_at
 */
#[ScopedBy([SportScope::class])]
final class Club extends Model
{
    /** @use HasFactory<ClubFactory> */
    use HasFactory, HasUuidTrait, SoftDeletes;

    /**
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'id' => 'integer',
            'uuid' => 'string',
            'name' => 'string',
            'known_as' => 'string',
            'official_name' => 'string',
            'code' => 'string',
            'bio' => 'string',
            'sport_id' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * @return HasMany<Team, $this>
     */
    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    /**
     * @return BelongsTo<Sport, $this>
     */
    public function sport(): BelongsTo
    {
        return $this->belongsTo(Sport::class);
    }

    /**
     * @return Attribute<string|null, string|null>
     */
    public function logo(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ? Storage::url($value) : null,
            set: fn (?string $value): ?string => $value,
        );
    }
}

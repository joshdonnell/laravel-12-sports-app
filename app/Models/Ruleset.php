<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TournamentTypes;
use App\Models\Scopes\SportScope;
use App\Traits\HasUuidTrait;
use Carbon\CarbonInterface;
use Database\Factories\RulesetFactory;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property-read string $uuid
 * @property-read string $name
 * @property-read string|null $description
 * @property-read TournamentTypes $type
 * @property-read int $sport_id
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 */
#[ScopedBy([SportScope::class])]
final class Ruleset extends Model
{
    /** @use HasFactory<RulesetFactory> */
    use HasFactory, HasUuidTrait;

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
            'type' => TournamentTypes::class,
            'sport_id' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * @return HasMany<Rule, $this>
     */
    public function rules(): HasMany
    {
        return $this->hasMany(Rule::class);
    }

    /**
     * @return BelongsTo<Sport, $this>
     */
    public function sport(): BelongsTo
    {
        return $this->belongsTo(Sport::class);
    }
}

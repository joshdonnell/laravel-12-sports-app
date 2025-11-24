<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Database\Factories\StatsRecordFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property-read int $stat_id
 * @property-read int|null $fixture_event_type_id
 * @property-read string|null $key
 * @property-read int $value
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 */
final class StatsRecord extends Model
{
    /** @use HasFactory<StatsRecordFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'id' => 'integer',
            'stat_id' => 'integer',
            'fixture_event_type_id' => 'integer',
            'key' => 'string',
            'value' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}

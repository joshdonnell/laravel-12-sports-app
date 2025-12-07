<?php

declare(strict_types=1);

namespace App\Data\Round;

use App\Models\Sport;
use Spatie\LaravelData\Attributes\Hidden;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
final class RoundData extends Data
{
    public function __construct(
        public string $uuid,
        public string $name,
        public int $round_number,
        public int $sport_id,
        #[Hidden, \Spatie\TypeScriptTransformer\Attributes\Hidden]
        public Sport $sport,
        public ?string $sport_name
    ) {
        $this->sport_name = $this->sport->name;
    }
}

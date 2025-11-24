<?php

declare(strict_types=1);

namespace App\Data\Club;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
final class ClubCardData extends Data
{
    public function __construct(
        public string $uuid,
        public string $name,
        public ?string $knownAs,
    ) {}
}

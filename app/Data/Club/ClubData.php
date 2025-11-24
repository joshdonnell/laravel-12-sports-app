<?php

declare(strict_types=1);

namespace App\Data\Club;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
final class ClubData extends Data
{
    public function __construct(
        public string $uuid,
        public string $name,
        public ?string $knownAs,
        public ?string $officialName,
        public ?string $code,
        public ?string $logo,
        public ?string $bio,
    ) {}
}

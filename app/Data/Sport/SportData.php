<?php

declare(strict_types=1);

namespace App\Data\Sport;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
final class SportData extends Data
{
    public function __construct(
        public string $uuid,
        public string $name,
    ) {}
}

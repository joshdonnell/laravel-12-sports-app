<?php

declare(strict_types=1);

namespace App\Data\Shared;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
final class SelectData extends Data
{
    public function __construct(
        public string $value,
        public string $label,
        #[Optional]
        public ?string $icon
    ) {}

    /**
     * @template T of mixed
     *
     * @param  T  $data
     * @return array<int|string, self>
     */
    public static function pick(string $valueField, string $labelField, ?string $iconField, mixed $data): array
    {
        /* @param  T  $item */
        $map = array_map(fn (mixed $item): array => [
            'value' => (string) $item[$valueField],
            'label' => (string) $item[$labelField],
            'icon' => $iconField ? (string) $item[$iconField] : null,
        ], $data);

        return self::collect($map);
    }
}

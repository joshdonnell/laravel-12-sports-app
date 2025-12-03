<?php

declare(strict_types=1);

namespace App\Data\User;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\Hidden;
use Spatie\LaravelData\Data;
use Spatie\Permission\Models\Role;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
final class UserData extends Data
{
    public function __construct(
        public string $uuid,
        public string $name,
        public string $email,
        public ?string $sport_id,
        /** @var Collection<string|int, Role> */
        #[Hidden, \Spatie\TypeScriptTransformer\Attributes\Hidden]
        public Collection $roles,
        public ?string $role_names,
        public ?\App\Enums\Role $role,
    ) {
        $this->role_names = collect($this->roles)
            ->pluck('name')
            ->map(fn (string $role): string => ucfirst($role))
            ->implode(', ');

        $this->role = \App\Enums\Role::tryFrom($this->roles->pluck('name')->first() ?: '');
    }
}

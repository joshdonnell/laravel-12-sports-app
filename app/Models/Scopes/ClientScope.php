<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

final readonly class ClientScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (! auth()->check()) {
            return;
        }

        $user = auth()->user();

        if (! $user || $user->hasRole(Role::SuperAdmin)) {
            return;
        }

        $builder->whereHas('clients', function (Builder $query) use ($user): void {
            $query->whereHas('users', function (Builder $q) use ($user): void {
                $q->where('users.id', $user->id);
            });
        });
    }
}

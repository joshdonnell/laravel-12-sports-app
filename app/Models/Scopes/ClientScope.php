<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

final class ClientScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (auth()->check()) {
            $user = auth()->user();
            if (! empty($user) && (! $user->hasRole(Role::SuperAdmin) || ! $user->hasRole(Role::Admin))) {
                $builder->whereHas('clients', function (Builder $query) use ($user): void {
                    $query->whereHas('users', function (Builder $q) use ($user): void {
                        $q->where('users.id', $user->id);
                    });
                });
            }
        }
    }
}

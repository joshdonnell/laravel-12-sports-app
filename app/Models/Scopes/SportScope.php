<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

final class SportScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (auth()->check()) {
            $user = auth()->user();
            if (! empty($user) && ! $user->hasRole(Role::SuperAdmin)) {
                $builder->where('sport_id', $user->sport_id);
            }
        }
    }
}

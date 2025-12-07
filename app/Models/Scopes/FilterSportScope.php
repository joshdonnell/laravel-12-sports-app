<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use App\Enums\Permission;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Stringable;

final readonly class FilterSportScope implements Scope
{
    public function __construct(private Stringable $sport, private User $user) {}

    /**
     * @template T of Model
     *
     * @param  Builder<T>  $builder
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (! $this->user->can(Permission::CREATE_SPORT)) {
            return;
        }

        if ($this->sport->isNotEmpty()) {
            $builder->where('sport_id', $this->sport->toString());
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Stringable;

final readonly class SearchScope implements Scope
{
    public function __construct(private Stringable $search, private ?string $column = 'name') {}

    /**
     * @template T of Model
     *
     * @param  Builder<T>  $builder
     */
    public function apply(Builder $builder, Model $model): void
    {
        if ($this->search->isNotEmpty() && $this->column !== null) {
            $builder->where($this->column, 'like', "%{$this->search}%");
        }
    }
}

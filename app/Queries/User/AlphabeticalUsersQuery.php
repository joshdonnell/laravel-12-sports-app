<?php

declare(strict_types=1);

namespace App\Queries\User;

use App\Models\Scopes\SportScope;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

final readonly class AlphabeticalUsersQuery
{
    /**
     * @return Builder<User>
     */
    public function builder(): Builder
    {
        return User::query()
            ->withGlobalScope('sport', new SportScope())
            ->usersWithLowerRole()
            ->excludeCurrentUser()
            ->orderBy('name');
    }
}

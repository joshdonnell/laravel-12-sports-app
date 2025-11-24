<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Data\User\UserData;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Spatie\Permission\Models\Permission;

final class HandleInertiaRequests extends Middleware
{
    /**
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'auth' => [
                'user' => empty($request->user()) ? null : UserData::from($request->user()),
                'can' => $request->user()?->getPermissionsViaRoles()
                    ->map(fn (Permission $permission): array => [$permission['name'] => auth()->user()?->can($permission['name'])])
                    ->collapse()
                    ->all(),
            ],
        ];
    }
}

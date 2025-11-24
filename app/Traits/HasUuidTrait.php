<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasUuidTrait
{
    public static function boot(): void
    {
        parent::boot();
        self::creating(function (Model $model): void {
            $model->attributes['uuid'] = Str::uuid();
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}

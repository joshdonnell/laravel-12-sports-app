<?php

declare(strict_types=1);

namespace App\Actions\Club;

use App\Models\Club;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

final readonly class CreateClub
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(array $attributes, ?UploadedFile $logo): Club
    {
        return DB::transaction(function () use ($attributes, $logo) {
            if ($logo instanceof UploadedFile) {
                $attributes['logo'] = $logo->storePublicly('clubs/logos');
            }

            return Club::query()->create($attributes);
        });
    }
}

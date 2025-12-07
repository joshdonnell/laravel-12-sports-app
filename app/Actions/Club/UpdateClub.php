<?php

declare(strict_types=1);

namespace App\Actions\Club;

use App\Actions\Shared\DeleteFile;
use App\Models\Club;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

final readonly class UpdateClub
{
    public function __construct(private DeleteFile $deleteFile) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(Club $club, array $attributes, ?UploadedFile $newLogo, bool $removeLogo): void
    {
        DB::transaction(function () use ($club, $attributes, $removeLogo, $newLogo): void {
            if ($newLogo instanceof UploadedFile) {
                $attributes['logo'] = $newLogo->storePublicly('clubs/logos');
            } elseif ($removeLogo) {
                $attributes['logo'] = null;
            }

            $club->update($attributes);

            if (($newLogo || $removeLogo) && $club->logo) {
                $this->deleteFile->handle($club->logo);
            }
        });
    }
}

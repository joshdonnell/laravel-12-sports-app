<?php

declare(strict_types=1);

namespace App\Actions\Club;

use App\Actions\Shared\DeleteFile;
use App\Models\Club;
use Illuminate\Support\Facades\DB;

final readonly class DeleteClub
{
    public function __construct(private DeleteFile $deleteFile) {}

    public function handle(Club $club): void
    {
        DB::transaction(function () use ($club): void {
            $club->delete();

            if ($club->logo) {
                $this->deleteFile->handle($club->logo);
            }
        });
    }
}

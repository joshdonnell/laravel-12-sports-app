<?php

declare(strict_types=1);

namespace App\Actions\Shared;

use Illuminate\Support\Facades\Storage;

final readonly class DeleteFile
{
    public function handle(string $path): void
    {
        if (! Storage::exists($path)) {
            return;
        }

        Storage::delete($path);
    }
}

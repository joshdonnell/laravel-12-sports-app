<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_venue', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('team_id')->constrained('teams');
            $table->foreignId('venue_id')->constrained('venues');
            $table->unique(['team_id', 'venue_id']);
            $table->timestamps();
        });
    }
};

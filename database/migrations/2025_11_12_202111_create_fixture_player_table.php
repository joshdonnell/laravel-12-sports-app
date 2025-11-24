<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fixture_player', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('fixture_id')->constrained('fixtures');
            $table->foreignId('player_id')->constrained('players');
            $table->unique(['fixture_id', 'player_id']);
            $table->timestamps();
        });
    }
};

<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('player_position', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('player_id')->constrained('players');
            $table->foreignId('position_id')->constrained('positions');
            $table->unique(['player_id', 'position_id']);
            $table->timestamps();
        });
    }
};

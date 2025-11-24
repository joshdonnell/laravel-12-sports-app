<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('standings', function (Blueprint $table): void {
            $table->id();
            $table->integer('position')->default(0);
            $table->integer('points')->default(0);
            $table->integer('bonus_points')->default(0);
            $table->integer('played')->default(0);
            $table->integer('won')->default(0);
            $table->integer('drawn')->default(0);
            $table->integer('lost')->default(0);
            $table->integer('score_for')->default(0);
            $table->integer('score_against')->default(0);
            $table->integer('score_difference')->default(0);
            $table->foreignId('season_id')->constrained('seasons');
            $table->foreignId('tournament_id')->constrained('tournaments');
            $table->foreignId('team_id')->constrained('teams');
            $table->timestamps();
        });
    }
};

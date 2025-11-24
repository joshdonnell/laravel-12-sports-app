<?php

declare(strict_types=1);

use App\Enums\FixtureStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fixtures', function (Blueprint $table): void {
            $table->id();
            $table->uuid()->unique();
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('week')->nullable();
            $table->date('date');
            $table->enum('status', array_column(FixtureStatus::cases(), 'value'))->default(FixtureStatus::Scheduled->value);
            $table->foreignId('home_team')->constrained('teams');
            $table->foreignId('away_team')->constrained('teams');
            $table->integer('home_team_score')->nullable();
            $table->integer('away_team_score')->nullable();
            $table->integer('home_team_bonus_score')->nullable();
            $table->integer('away_team_bonus_score')->nullable();
            $table->foreignId('venue_id')->constrained('venues');
            $table->foreignId('tournament_id')->constrained('tournaments');
            $table->foreignId('round_id')->nullable()->constrained('rounds');
            $table->timestamps();
            $table->softDeletes();
        });
    }
};

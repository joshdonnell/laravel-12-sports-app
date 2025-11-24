<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fixture_events', function (Blueprint $table): void {
            $table->id();
            $table->integer('event_time');
            $table->integer('event_period')->nullable();
            $table->json('event_metadata')->nullable();
            $table->foreignId('fixture_id')->constrained('fixtures');
            $table->foreignId('team_id')->constrained('teams');
            $table->foreignId('player_id')->constrained('players');
            $table->foreignId('fixture_event_type_id')->constrained('fixture_event_types');
            $table->timestamps();
        });
    }
};

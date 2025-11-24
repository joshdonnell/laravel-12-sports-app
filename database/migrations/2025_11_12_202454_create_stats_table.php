<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stats', function (Blueprint $table): void {
            $table->id();
            $table->morphs('statable');
            $table->foreignId('season_id')->constrained('seasons');
            $table->foreignId('fixture_id')->nullable()->constrained('fixtures');
            $table->foreignId('tournament_id')->nullable()->constrained('tournaments');
            $table->timestamps();
        });
    }
};

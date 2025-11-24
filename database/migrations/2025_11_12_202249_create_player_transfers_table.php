<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('player_transfers', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('player_id')->constrained('players');
            $table->foreignId('from_team_id')->nullable()->constrained('teams');
            $table->foreignId('to_team_id')->nullable()->constrained('teams');
            $table->date('transfer_date');
            $table->timestamps();
        });
    }
};

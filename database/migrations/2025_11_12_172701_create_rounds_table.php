<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rounds', function (Blueprint $table): void {
            $table->id();
            $table->uuid()->unique();
            $table->string('name');
            $table->integer('round_number');
            $table->foreignId('sport_id')->constrained('sports');
            $table->unique(['name', 'sport_id']);
            $table->timestamps();
            $table->softDeletes();
        });
    }
};

<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tournaments', function (Blueprint $table): void {
            $table->id();
            $table->uuid()->unique();
            $table->string('name');
            $table->string('code');
            $table->boolean('exclude_stats')->default(false);
            $table->foreignId('sport_id')->constrained('sports');
            $table->foreignId('ruleset_id')->constrained('rulesets');
            $table->timestamps();
            $table->softDeletes();
        });
    }
};

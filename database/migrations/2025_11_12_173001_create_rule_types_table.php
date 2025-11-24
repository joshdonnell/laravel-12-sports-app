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
        Schema::create('rule_types', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('key');
            $table->foreignId('sport_id')->nullable()->constrained('sports');
            $table->unique(['sport_id', 'key']);
            $table->timestamps();
        });
    }
};

<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('venues', function (Blueprint $table): void {
            $table->id();
            $table->uuid()->unique();
            $table->string('name');
            $table->longText('address')->nullable();
            $table->string('website')->nullable();
            $table->foreignId('sport_id')->constrained('sports');
            $table->unique(['sport_id', 'name']);
            $table->timestamps();
            $table->softDeletes();
        });
    }
};

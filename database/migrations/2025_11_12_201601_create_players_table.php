<?php

declare(strict_types=1);

use App\Enums\Genders;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table): void {
            $table->id();
            $table->uuid()->unique();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('known_as')->nullable();
            $table->string('match_name')->nullable();
            $table->enum('gender', array_column(Genders::cases(), 'value'))->nullable();
            $table->date('date_of_birth')->nullable();
            $table->foreignId('country_id')->constrained('countries');
            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            $table->string('profile_picture')->nullable();
            $table->foreignId('sport_id')->constrained('sports');
            $table->timestamps();
            $table->softDeletes();
        });
    }
};

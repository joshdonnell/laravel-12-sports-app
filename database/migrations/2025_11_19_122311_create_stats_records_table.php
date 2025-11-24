<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stats_records', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('stat_id')->constrained('stats');
            $table->foreignId('fixture_event_type_id')->constrained('fixture_event_types');
            $table->string('key')->nullable();
            $table->integer('value');
            $table->unique(['stat_id', 'fixture_event_type_id']);
            $table->timestamps();
        });
    }
};

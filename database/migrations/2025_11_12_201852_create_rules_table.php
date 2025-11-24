<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rules', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('ruleset_id')->constrained('rulesets');
            $table->foreignId('rule_type_id')->constrained('rule_types');
            $table->string('value');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }
};

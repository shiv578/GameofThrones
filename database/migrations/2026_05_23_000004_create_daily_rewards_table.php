<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_rewards', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('day_number')->unique(); // 1-7
            $table->unsignedInteger('coins_reward')->default(0);
            $table->unsignedTinyInteger('diamonds_min')->default(0);
            $table->unsignedTinyInteger('diamonds_max')->default(0);
            $table->unsignedTinyInteger('diamond_chance')->default(0); // Percentage 0-100
            $table->string('box_type')->nullable(); // epic, rare, mystery — never mythic
            $table->string('label')->nullable(); // Display label e.g. "Day 1"
            $table->string('icon')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_rewards');
    }
};

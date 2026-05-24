<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_quests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type', 30); // daily, weekly
            $table->string('requirement_type', 50); // play_games, win_games, earn_xp, earn_coins, open_boxes, login
            $table->unsignedInteger('requirement_value')->default(1);
            $table->unsignedInteger('reward_coins')->default(0);
            $table->unsignedInteger('reward_diamonds')->default(0);
            $table->unsignedInteger('reward_xp')->default(0);
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_quests');
    }
};

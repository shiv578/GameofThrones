<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reward_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('source_type', 50); // daily_reward, mystery_box, achievement, game, quest, event
            $table->unsignedBigInteger('source_id')->nullable();
            $table->unsignedInteger('coins_earned')->default(0);
            $table->unsignedInteger('diamonds_earned')->default(0);
            $table->unsignedInteger('xp_earned')->default(0);
            $table->json('items_earned')->nullable(); // avatars, borders, themes
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'source_type']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reward_history');
    }
};

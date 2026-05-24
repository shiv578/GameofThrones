<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_boxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mystery_box_id')->constrained('mystery_boxes')->cascadeOnDelete();
            $table->string('source', 50); // daily_reward, shop, event, achievement
            $table->boolean('is_opened')->default(false);
            $table->timestamp('opened_at')->nullable();
            $table->unsignedInteger('reward_coins')->nullable();
            $table->unsignedTinyInteger('reward_diamonds')->nullable();
            $table->json('reward_items')->nullable(); // avatars, borders, etc.
            $table->timestamps();

            $table->index(['user_id', 'is_opened']);
            $table->index('source');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_boxes');
    }
};

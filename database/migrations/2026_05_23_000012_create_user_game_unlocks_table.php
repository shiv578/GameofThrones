<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_game_unlocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('premium_game_id')->constrained('premium_games')->cascadeOnDelete();
            $table->string('unlock_method', 30); // diamonds, razorpay, stripe
            $table->foreignId('purchase_id')->nullable()->constrained('user_purchases')->nullOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'premium_game_id']); // Prevent duplicate unlocks
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_game_unlocks');
    }
};

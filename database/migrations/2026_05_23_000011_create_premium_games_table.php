<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('premium_games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->unique()->constrained('games')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('price_diamonds')->default(500);
            $table->unsignedInteger('price_inr')->default(50000); // ₹500 in paise
            $table->unsignedInteger('price_usd')->default(500); // $5 in cents
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('premium_games');
    }
};

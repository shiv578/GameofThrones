<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['credit', 'debit']);
            $table->enum('currency', ['coins', 'diamonds']);
            $table->unsignedBigInteger('amount');
            $table->unsignedBigInteger('balance_after');
            $table->string('source', 50); // daily_reward, game_score, box_open, shop_purchase, achievement, admin
            $table->string('reference_type')->nullable(); // Polymorphic type
            $table->unsignedBigInteger('reference_id')->nullable(); // Polymorphic id
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'currency']);
            $table->index(['user_id', 'created_at']);
            $table->index('source');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};

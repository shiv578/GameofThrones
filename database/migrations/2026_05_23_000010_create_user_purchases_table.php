<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shop_item_id')->nullable()->constrained('shop_items')->nullOnDelete();
            $table->string('item_name'); // Denormalized for history even if item deleted
            $table->string('payment_method', 30); // coins, diamonds, razorpay, stripe
            $table->string('payment_currency', 10)->nullable(); // INR, USD
            $table->unsignedInteger('amount_paid')->default(0);
            $table->string('gateway_order_id')->nullable();
            $table->string('gateway_payment_id')->nullable();
            $table->string('gateway_signature')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('gateway_order_id');
            $table->index('gateway_payment_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_purchases');
    }
};

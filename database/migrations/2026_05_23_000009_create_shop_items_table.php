<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category', 30); // avatar, border, theme, mystery_box, coin_pack, diamond_pack, premium_game, bundle
            $table->string('image')->nullable();
            $table->string('rarity', 20)->default('common'); // common, uncommon, rare, epic, legendary

            // Pricing - multiple currencies supported
            $table->unsignedInteger('price_coins')->nullable();
            $table->unsignedInteger('price_diamonds')->nullable();
            $table->unsignedInteger('price_inr')->nullable(); // In paise (₹100 = 10000)
            $table->unsignedInteger('price_usd')->nullable(); // In cents ($5 = 500)

            // Discount system
            $table->unsignedTinyInteger('discount_percent')->nullable();
            $table->unsignedInteger('original_price_coins')->nullable();
            $table->unsignedInteger('original_price_diamonds')->nullable();

            // Stock & availability
            $table->boolean('is_limited')->default(false);
            $table->unsignedInteger('stock')->nullable();
            $table->timestamp('available_from')->nullable();
            $table->timestamp('expires_at')->nullable();

            // Item data
            $table->json('item_data')->nullable(); // Avatar URL, border CSS, theme config, etc.
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index('category');
            $table->index('is_active');
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_items');
    }
};

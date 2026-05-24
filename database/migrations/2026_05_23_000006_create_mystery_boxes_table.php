<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mystery_boxes', function (Blueprint $table) {
            $table->id();
            $table->string('type', 30)->unique(); // epic, rare, mystery, mythic
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('rarity', 20); // common, uncommon, rare, legendary
            $table->string('glow_color', 30); // CSS color for UI effects
            $table->unsignedInteger('min_coins')->default(0);
            $table->unsignedInteger('max_coins')->default(0);
            $table->unsignedTinyInteger('min_diamonds')->default(0);
            $table->unsignedTinyInteger('max_diamonds')->default(0);
            $table->boolean('grants_avatar')->default(false);
            $table->boolean('grants_border')->default(false);
            $table->string('availability', 30)->default('always'); // always, event, seasonal, achievement
            $table->unsignedInteger('shop_price_coins')->nullable();
            $table->unsignedInteger('shop_price_diamonds')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mystery_boxes');
    }
};

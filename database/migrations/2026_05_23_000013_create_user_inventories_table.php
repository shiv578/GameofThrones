<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('item_type', 30); // avatar, border, theme, badge, title
            $table->string('item_key'); // Unique identifier for the item
            $table->string('item_name');
            $table->json('item_data')->nullable(); // Image URL, CSS config, etc.
            $table->string('source', 50); // shop, mystery_box, achievement, event, default
            $table->unsignedBigInteger('source_id')->nullable();
            $table->boolean('is_equipped')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'item_type']);
            $table->unique(['user_id', 'item_type', 'item_key']); // No duplicate items
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_inventories');
    }
};

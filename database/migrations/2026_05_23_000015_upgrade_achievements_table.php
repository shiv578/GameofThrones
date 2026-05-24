<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Upgrade existing achievements table with new fields.
     * Existing columns are preserved — only new columns added.
     */
    public function up(): void
    {
        Schema::table('achievements', function (Blueprint $table) {
            $table->string('category', 30)->default('general')->after('description');
            // login, games_played, wins, accuracy, puzzle, streaks, special, seasonal
            $table->string('rarity', 20)->default('common')->after('category');
            // common, uncommon, rare, epic, legendary
            $table->unsignedInteger('diamond_reward')->default(0)->after('coin_reward');
            $table->unsignedInteger('progress_target')->default(1)->after('requirement_value');
            $table->string('badge_icon')->nullable()->after('icon');
            $table->string('badge_color', 30)->nullable()->after('badge_icon');
            $table->boolean('is_claimable')->default(true)->after('badge_color');
            $table->boolean('is_active')->default(true)->after('is_claimable');
            $table->unsignedInteger('sort_order')->default(0)->after('is_active');

            $table->index('category');
            $table->index('rarity');
        });
    }

    public function down(): void
    {
        Schema::table('achievements', function (Blueprint $table) {
            $table->dropIndex(['category']);
            $table->dropIndex(['rarity']);

            $table->dropColumn([
                'category',
                'rarity',
                'diamond_reward',
                'progress_target',
                'badge_icon',
                'badge_color',
                'is_claimable',
                'is_active',
                'sort_order',
            ]);
        });
    }
};

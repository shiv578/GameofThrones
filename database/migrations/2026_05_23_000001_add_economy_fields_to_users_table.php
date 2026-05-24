<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add economy, ranking, and login tracking fields to users table.
     * Does NOT modify any existing columns.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Premium currency
            $table->unsignedInteger('diamonds')->default(0)->after('coins');

            // Ranking system
            $table->string('rank', 20)->default('Bronze')->after('level');

            // Login streak tracking
            $table->unsignedInteger('total_login_days')->default(0)->after('rank');
            $table->date('last_login_date')->nullable()->after('total_login_days');
            $table->unsignedInteger('login_streak')->default(0)->after('last_login_date');
            $table->unsignedInteger('current_streak')->default(0)->after('login_streak');

            // Cosmetics
            $table->string('avatar_border')->nullable()->after('avatar');
            $table->string('equipped_theme')->nullable()->after('avatar_border');

            // Indexes for leaderboard queries
            $table->index('diamonds');
            $table->index('rank');
            $table->index('total_login_days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['diamonds']);
            $table->dropIndex(['rank']);
            $table->dropIndex(['total_login_days']);

            $table->dropColumn([
                'diamonds',
                'rank',
                'total_login_days',
                'last_login_date',
                'login_streak',
                'current_streak',
                'avatar_border',
                'equipped_theme',
            ]);
        });
    }
};

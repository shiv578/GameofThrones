<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leaderboards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('total_xp')->default(0);
            $table->integer('total_coins')->default(0);
            $table->integer('games_played')->default(0);
            $table->integer('highest_score')->default(0);
            $table->integer('win_count')->default(0);
            $table->integer('rank')->nullable();
            $table->string('period')->default('all-time'); // all-time, weekly
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaderboards');
    }
};

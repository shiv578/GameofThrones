<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('login_streaks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->unsignedInteger('streak_count')->default(0);
            $table->unsignedInteger('longest_streak')->default(0);
            $table->date('last_claim_date')->nullable();
            $table->boolean('claimed_today')->default(false);
            $table->unsignedInteger('total_claims')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_streaks');
    }
};

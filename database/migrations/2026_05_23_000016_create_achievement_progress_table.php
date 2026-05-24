<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('achievement_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('achievement_id')->constrained('achievements')->cascadeOnDelete();
            $table->unsignedInteger('current_progress')->default(0);
            $table->boolean('is_completed')->default(false);
            $table->boolean('is_claimed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('claimed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'achievement_id']); // One progress row per achievement per user
            $table->index(['user_id', 'is_completed', 'is_claimed']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('achievement_progress');
    }
};

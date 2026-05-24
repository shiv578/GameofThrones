<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_quests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('daily_quest_id')->constrained('daily_quests')->cascadeOnDelete();
            $table->unsignedInteger('current_progress')->default(0);
            $table->boolean('is_completed')->default(false);
            $table->boolean('is_claimed')->default(false);
            $table->date('assigned_date'); // When this quest was assigned
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('claimed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'daily_quest_id', 'assigned_date']);
            $table->index(['user_id', 'assigned_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_quests');
    }
};

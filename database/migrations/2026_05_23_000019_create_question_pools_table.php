<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question_pools', function (Blueprint $table) {
            $table->id();
            $table->string('category', 30); // history, science, coding, general
            $table->string('difficulty', 20)->default('medium'); // easy, medium, hard
            $table->text('question');
            $table->json('options'); // Array of 4 options
            $table->string('correct_answer');
            $table->text('explanation')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['category', 'difficulty']);
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_pools');
    }
};

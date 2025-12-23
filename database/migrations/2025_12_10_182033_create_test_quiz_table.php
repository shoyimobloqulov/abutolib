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
        Schema::create('test_quiz', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained('tests')->onDelete('cascade');
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');

            // Savolning test ichidagi tartibi
            $table->unsignedInteger('order')->nullable();

            // Savol uchun berilgan ball (agar test har bir savolga ball bersa)
            $table->unsignedInteger('points')->default(1);
            $table->unique(['test_id', 'quiz_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_quiz');
    }
};

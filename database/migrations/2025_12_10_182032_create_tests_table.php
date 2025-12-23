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
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            // Test turi: yopiq (closed) yoki ochiq (open)
            $table->enum('type', ['yopiq', 'ochiq'])->default('yopiq');

            // Test nomi va batafsil ma'lumot
            $table->string('title');
            $table->text('description')->nullable();

            // Statistikalar / sozlamalar
            $table->unsignedInteger('questions_count')->default(0); // Savollar soni (taxminiy yoki avtomatik to'ldiriladi)
            $table->dateTime('start_at')->nullable();
            $table->unsignedInteger('duration_minutes')->nullable(); // davomiylik (daqiqa)
            $table->dateTime('end_at')->nullable();

            $table->unsignedInteger('passing_score')->default(0); // o'tish bali (percent yoki ball - siz qaysi formatni ishlaysaniz)
            $table->unsignedInteger('attempts_allowed')->default(1); // urinishlar soni

            // Test sozlamalari
            $table->boolean('show_answers')->default(false); // Javoblarni ko'rsatish
            $table->boolean('shuffle_questions')->default(false); // Savollarni aralashtirish
            $table->boolean('shuffle_answers')->default(false); // Variantlarni aralashtirish

            // Qo'shimcha: owner yoki subject/tip uchun foreignKey kerak bo'lsa quyidagicha qo'shing:
            // $table->foreignId('subject_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};

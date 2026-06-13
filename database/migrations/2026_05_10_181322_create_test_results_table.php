<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Создание таблицы результатов прохождения тестов
     */
    public function up(): void
    {
        Schema::create('test_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('test_id')->constrained()->onDelete('cascade');
            $table->integer('score'); // Набрано баллов
            $table->integer('max_score'); // Максимум возможных
            $table->decimal('percentage', 5, 2)->nullable(); // Процент выполнения
            $table->integer('time_spent')->nullable(); // Время в секундах
            $table->timestamp('completed_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_results');
    }
};
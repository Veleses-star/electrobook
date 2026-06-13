<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Создание таблицы уровней сложности
     * Коды соответствуют названиям: novice, amateur, pro, expert
     */
    public function up(): void
    {
        Schema::create('difficulty_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50); // Отображаемое название
            $table->enum('code', ['novice', 'amateur', 'pro', 'expert'])->unique();
            $table->string('color', 20); // HEX-цвет для оформления в интерфейсе
            $table->integer('min_points')->default(0); // Мин. баллов для доступа
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('difficulty_levels');
    }
};
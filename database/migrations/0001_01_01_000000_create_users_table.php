<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Создание таблицы пользователей
     * Добавлены поля для ролей, кастомизации профиля и системы баллов
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken(); // Нужно для работы авторизации Laravel
            // Роль: student (ученик) или admin (администратор)
            $table->enum('role', ['student', 'admin'])->default('student');
            // Элементы кастомизации профиля
            $table->string('avatar')->default('default.png');
            $table->string('frame')->default('default.png');
            $table->string('theme')->default('light');
            // Игровая валюта (баллы)
            $table->integer('points')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Откат миграции: удаление таблицы
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
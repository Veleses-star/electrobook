<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Удаляем золотую рамку (тип 'frame')
        DB::table('shop_items')->where('type', 'frame')->delete();

        // 2. Удаляем все старые статусы, если были (тип 'status')
        DB::table('shop_items')->where('type', 'status')->delete();

        // 3. Вставляем русские статусы
        $now = now();
        DB::table('shop_items')->insert([
            ['name' => 'Молодец!', 'type' => 'status', 'price' => 50, 'description' => 'Отображается на вашем профиле как почётный статус', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Умный', 'type' => 'status', 'price' => 100, 'description' => 'Статус для самых сообразительных', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Отличник', 'type' => 'status', 'price' => 150, 'description' => 'Только для настоящих отличников', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Знайка', 'type' => 'status', 'price' => 200, 'description' => 'Кто много читает, тот много знает', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Лидер', 'type' => 'status', 'price' => 250, 'description' => 'Статус тех, кто ведёт за собой', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Эрудит', 'type' => 'status', 'price' => 300, 'description' => 'Широкий кругозор – твоя суперсила', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Мастер', 'type' => 'status', 'price' => 350, 'description' => 'Ты достиг мастерства в учёбе', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Гений', 'type' => 'status', 'price' => 400, 'description' => 'Выдающиеся способности', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Звезда', 'type' => 'status', 'price' => 450, 'description' => 'Сияй ярче всех', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Легенда', 'type' => 'status', 'price' => 500, 'description' => 'Твоё имя запомнят надолго', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 4. Добавляем поле selected_status в таблицу users, если его ещё нет
        if (!Schema::hasColumn('users', 'selected_status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('selected_status')->nullable()->after('frame_class');
            });
        }
    }

    public function down()
    {
        // Удаляем все статусы
        DB::table('shop_items')->where('type', 'status')->delete();

        // Удаляем поле selected_status из users
        if (Schema::hasColumn('users', 'selected_status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('selected_status');
            });
        }
        // Золотую рамку не восстанавливаем автоматически (при необходимости можно добавить)
    }
};
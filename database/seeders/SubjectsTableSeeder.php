<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject; // Импортируем модель

class SubjectsTableSeeder extends Seeder
{
    /**
     * Запуск сидера: заполнение таблицы subjects
     */
    public function run(): void
    {
        $subjects = [
            [
                'name' => 'Математика',
                'icon' => 'math_icon.svg', // Иконка-заглушка
                'description' => 'Арифметика, геометрия и логика для начальной школы'
            ],
            [
                'name' => 'Русский язык',
                'icon' => 'russian_icon.svg',
                'description' => 'Грамматика, орфография и пунктуация'
            ],
            [
                'name' => 'Окружающий мир',
                'icon' => 'world_icon.svg',
                'description' => 'Природа, общество и безопасность'
            ],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }
    }
}
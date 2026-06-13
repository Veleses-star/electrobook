<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DifficultyLevel;

class DifficultyLevelsTableSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            ['name' => '1 класс', 'code' => 'class_1', 'color' => '#4CAF50', 'min_points' => 0],
            ['name' => '2 класс', 'code' => 'class_2', 'color' => '#2196F3', 'min_points' => 100],
            ['name' => '3 класс', 'code' => 'class_3', 'color' => '#FF9800', 'min_points' => 250],
            ['name' => '4 класс', 'code' => 'class_4', 'color' => '#9C27B0', 'min_points' => 500],
        ];

        foreach ($levels as $level) {
            DifficultyLevel::create($level);
        }
    }
}
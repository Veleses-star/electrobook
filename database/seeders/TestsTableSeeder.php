<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Test;
use App\Models\Question;
use App\Models\Answer;

class TestsTableSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Создаём тест по Математике (Новичок)
        $test = Test::create([
            'subject_id' => 1,      // Математика
            'difficulty_id' => 1,   // Новичок
            'title' => 'Сложение и вычитание',
            'description' => 'Базовые примеры для 1 класса',
            'points_for_completion' => 5,
            'is_active' => true,
        ]);

        // Вопрос 1
        $q1 = Question::create(['test_id' => $test->id, 'question_text' => 'Сколько будет 5 + 3?', 'question_type' => 'single_choice', 'points' => 1]);
        Answer::create(['question_id' => $q1->id, 'answer_text' => '7', 'is_correct' => false]);
        Answer::create(['question_id' => $q1->id, 'answer_text' => '8', 'is_correct' => true]);
        Answer::create(['question_id' => $q1->id, 'answer_text' => '9', 'is_correct' => false]);

        // Вопрос 2
        $q2 = Question::create(['test_id' => $test->id, 'question_text' => 'Какое число больше: 12 или 9?', 'question_type' => 'single_choice', 'points' => 1]);
        Answer::create(['question_id' => $q2->id, 'answer_text' => '9', 'is_correct' => false]);
        Answer::create(['question_id' => $q2->id, 'answer_text' => '12', 'is_correct' => true]);

        // Вопрос 3
        $q3 = Question::create(['test_id' => $test->id, 'question_text' => '10 - 4 = ?', 'question_type' => 'single_choice', 'points' => 1]);
        Answer::create(['question_id' => $q3->id, 'answer_text' => '5', 'is_correct' => false]);
        Answer::create(['question_id' => $q3->id, 'answer_text' => '6', 'is_correct' => true]);
        Answer::create(['question_id' => $q3->id, 'answer_text' => '7', 'is_correct' => false]);
    }
}
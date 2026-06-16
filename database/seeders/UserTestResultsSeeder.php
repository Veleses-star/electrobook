<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Test;
use App\Models\TestResult;
use Illuminate\Support\Facades\Hash;

class UserTestResultsSeeder extends Seeder
{
    public function run()
    {
        $faker = fake(); // Встроенный хелпер Laravel
        $tests = Test::where('is_active', true)->get();
        $numUsers = 30;

        if ($tests->isEmpty()) {
            $this->command->error('Нет активных тестов! Сначала создайте тесты.');
            return;
        }

        // Создаём 30 учеников
        $users = [];
        for ($i = 0; $i < $numUsers; $i++) {
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('12345678'),
                'role' => 'student',
                'points' => 0,
            ]);
            $users[] = $user;
        }

        // Для каждого теста создаём результаты для случайных 5–15 учеников
        foreach ($tests as $test) {
            $numResults = rand(5, 15);
            $randomUsers = $faker->randomElements($users, $numResults);
            $maxScore = $test->questions->count();
            if ($maxScore == 0) continue; // пропускаем тесты без вопросов

            foreach ($randomUsers as $user) {
                $score = rand(0, $maxScore);
                $percentage = ($maxScore > 0) ? round(($score / $maxScore) * 100, 2) : 0;
                $completedAt = $faker->dateTimeBetween('-30 days', 'now');

                TestResult::create([
                    'user_id' => $user->id,
                    'test_id' => $test->id,
                    'score' => $score,
                    'max_score' => $maxScore,
                    'percentage' => $percentage,
                    'completed_at' => $completedAt,
                ]);

                $user->points += $score;
                $user->save();
            }
        }

        // Дополнительные случайные результаты (0–5 на ученика)
        foreach ($users as $user) {
            $additionalTests = $faker->randomElements($tests, rand(0, 5));
            foreach ($additionalTests as $test) {
                $exists = TestResult::where('user_id', $user->id)
                                    ->where('test_id', $test->id)
                                    ->exists();
                if (!$exists) {
                    $maxScore = $test->questions->count();
                    if ($maxScore == 0) continue;
                    $score = rand(0, $maxScore);
                    $percentage = ($maxScore > 0) ? round(($score / $maxScore) * 100, 2) : 0;
                    $completedAt = $faker->dateTimeBetween('-30 days', 'now');

                    TestResult::create([
                        'user_id' => $user->id,
                        'test_id' => $test->id,
                        'score' => $score,
                        'max_score' => $maxScore,
                        'percentage' => $percentage,
                        'completed_at' => $completedAt,
                    ]);

                    $user->points += $score;
                    $user->save();
                }
            }
        }

        $this->command->info("✅ Создано 30 учеников и сгенерированы результаты тестов.");
        $this->command->info("Пароль для всех учеников: 12345678");
    }
}
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
        $tests = Test::where('is_active', true)->get();

        if ($tests->isEmpty()) {
            $this->command->error('Нет активных тестов! Сначала создайте тесты.');
            return;
        }

        // Массивы для генерации имён
        $firstNames = ['Алексей', 'Максим', 'Дмитрий', 'Андрей', 'Сергей', 'Анна', 'Екатерина', 'Ольга', 'Татьяна', 'Виктор', 'Елена', 'Ирина', 'Мария', 'Владимир', 'Александр', 'Даниил', 'Михаил', 'Никита', 'Артём', 'Иван', 'София', 'Алиса', 'Вероника', 'Анастасия', 'Полина', 'Егор', 'Роман', 'Матвей', 'Тимофей', 'Кирилл'];
        $lastNames = ['Иванов', 'Петров', 'Сидоров', 'Кузнецов', 'Смирнов', 'Попов', 'Фёдоров', 'Морозов', 'Волков', 'Алексеев', 'Лебедев', 'Соколов', 'Новиков', 'Козлов', 'Медведев', 'Егоров', 'Сергеев', 'Карпов', 'Михайлов', 'Николаев'];

        // Генерируем уникальные email
        $usedEmails = [];

        // Создаём 30 учеников
        $users = [];
        for ($i = 0; $i < 30; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $name = $firstName . ' ' . $lastName;

            // Генерируем уникальный email
            do {
                $email = strtolower($firstName . '.' . $lastName . rand(1, 999)) . '@example.com';
            } while (in_array($email, $usedEmails));
            $usedEmails[] = $email;

            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('12345678'),
                'role' => 'student',
                'points' => 0,
            ]);
            $users[] = $user;
        }

        // Для каждого теста создаём результаты для случайных 5–15 учеников
        foreach ($tests as $test) {
            $numResults = rand(5, 15);
            $randomUsers = $this->randomElements($users, $numResults);
            $maxScore = $test->questions->count();
            if ($maxScore == 0) continue; // пропускаем тесты без вопросов

            foreach ($randomUsers as $user) {
                $score = rand(0, $maxScore);
                $percentage = ($maxScore > 0) ? round(($score / $maxScore) * 100, 2) : 0;
                $completedAt = $this->randomDate('-30 days');

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
            $additionalTests = $this->randomElements($tests, rand(0, 5));
            foreach ($additionalTests as $test) {
                $exists = TestResult::where('user_id', $user->id)
                                    ->where('test_id', $test->id)
                                    ->exists();
                if (!$exists) {
                    $maxScore = $test->questions->count();
                    if ($maxScore == 0) continue;
                    $score = rand(0, $maxScore);
                    $percentage = ($maxScore > 0) ? round(($score / $maxScore) * 100, 2) : 0;
                    $completedAt = $this->randomDate('-30 days');

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

    // Вспомогательный метод для случайной выборки элементов (без Faker)
    private function randomElements($array, $count)
    {
        shuffle($array);
        return array_slice($array, 0, $count);
    }

    // Вспомогательный метод для случайной даты
    private function randomDate($modify)
    {
        $timestamp = strtotime($modify);
        $randomTimestamp = rand($timestamp, time());
        return date('Y-m-d H:i:s', $randomTimestamp);
    }
}
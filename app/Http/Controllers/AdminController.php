<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Test;
use App\Models\Subject;
use App\Models\DifficultyLevel;
use App\Models\Question;
use App\Models\Answer;
use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Главная панель администратора
     */
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Доступ запрещен');
        }

        $stats = [
            'users' => User::count(),
            'tests' => Test::count(),
            'results' => TestResult::count(),
            'total_points' => User::sum('points'),
        ];

        $recentResults = TestResult::with(['user', 'test.subject'])
                                   ->orderBy('completed_at', 'desc')
                                   ->take(5)
                                   ->get();

        return view('admin.index', compact('stats', 'recentResults'));
    }

    /**
     * Страница создания нового теста
     */
    public function createTest()
    {
        $subjects = Subject::all();
        $levels = DifficultyLevel::all();
        return view('admin.tests.create', compact('subjects', 'levels'));
    }

    /**
     * Сохранение нового теста в БД
     */
    public function storeTest(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'difficulty_id' => 'required|exists:difficulty_levels,id',
            'points_for_completion' => 'required|integer|min:0',
        ]);

        Test::create($validated);

        return redirect()->route('admin.index')->with('success', 'Тест успешно создан!');
    }

    /**
     * Страница добавления вопроса к конкретному тесту
     */
    public function addQuestion($testId)
    {
        $test = Test::findOrFail($testId);
        return view('admin.questions.create', compact('test'));
    }

    /**
     * Сохранение вопроса и вариантов ответов
     */
    public function storeQuestion(Request $request, $testId)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:single_choice,multiple_choice,text_input,matching',
            'points' => 'required|integer|min:1',
            'answers' => 'required|array|min:2',
            'correct_answer' => 'required|array|min:1',
        ]);

        // Создаём вопрос
        $question = Question::create([
            'test_id' => $testId,
            'question_text' => $validated['question_text'],
            'question_type' => $validated['question_type'],
            'points' => $validated['points'],
        ]);

        // Сохраняем варианты ответов
        foreach ($validated['answers'] as $index => $text) {
            if (empty(trim($text))) continue;
            
            $isCorrect = in_array($index, $validated['correct_answer']);
            
            Answer::create([
                'question_id' => $question->id,
                'answer_text' => trim($text),
                'is_correct' => $isCorrect,
                'order_index' => $index,
            ]);
        }

        return redirect()->route('admin.questions.create', $testId)
                         ->with('success', 'Вопрос и ответы добавлены!');
    }
    
    /**
     * Экспорт результатов в CSV
     */
    public function exportCsv()
    {
        $filename = "otchet_testy_" . date('Y-m-d') . ".csv";
        
        // Заголовки для корректного открытия в Excel
        header('Content-Encoding: UTF-8');
        header('Content-type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        // BOM (Byte Order Mark) - обязателен, чтобы русские буквы не превратились в кракозябры
        echo "\xEF\xBB\xBF";

        // Получаем все результаты с данными пользователей и тестов
        $results = TestResult::with(['user', 'test.subject'])->orderBy('completed_at', 'desc')->get();

        // Открываем поток вывода
        $output = fopen('php://output', 'w');

        // Заголовки таблицы
        fputcsv($output, ['Ученик', 'Тест', 'Предмет', 'Результат (%)', 'Дата'], ";");

        // Строки данных
        foreach ($results as $result) {
            fputcsv($output, [
                $result->user->name,
                $result->test->title,
                $result->test->subject->name,
                $result->percentage . '%',
                date('d.m.Y', strtotime($result->completed_at))
            ], ";");
        }

        fclose($output);
        exit;
    }

}
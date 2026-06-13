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
    // Главная админ-панель
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
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

    // Управление тестами (список)
    public function manageTests()
    {
        $subjects = Subject::with('tests.difficulty')->get();
        return view('admin.tests.manage', compact('subjects'));
    }

    // Форма создания теста (с предустановленными предметом и классом)
    public function createTest(Request $request)
    {
        $subjects = Subject::all();
        $levels = DifficultyLevel::all();
        $selectedSubject = $request->query('subject_id');
        $selectedDifficulty = $request->query('difficulty_id');
        return view('admin.tests.create', compact('subjects', 'levels', 'selectedSubject', 'selectedDifficulty'));
    }

    // Сохранение нового теста
    public function storeTest(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'difficulty_id' => 'required|exists:difficulty_levels,id',
            'points_for_completion' => 'required|integer|min:0',
            'theory' => 'nullable|string',
        ]);
        Test::create($validated);
        return redirect()->route('admin.tests.manage')->with('success', 'Тест создан');
    }

    // Форма редактирования теста
    public function editTest($id)
    {
        $test = Test::findOrFail($id);
        $subjects = Subject::all();
        $levels = DifficultyLevel::all();
        return view('admin.tests.create', compact('test', 'subjects', 'levels'));
    }

    // Обновление теста
    public function updateTest(Request $request, $id)
    {
        $test = Test::findOrFail($id);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'difficulty_id' => 'required|exists:difficulty_levels,id',
            'points_for_completion' => 'required|integer|min:0',
            'theory' => 'nullable|string',
        ]);
        $test->update($validated);
        return redirect()->route('admin.tests.manage')->with('success', 'Тест обновлён');
    }

    // Удаление теста
    public function deleteTest($id)
    {
        $test = Test::findOrFail($id);
        $test->delete();
        return redirect()->route('admin.tests.manage')->with('success', 'Тест удалён');
    }

    // Управление вопросами (список + форма добавления)
    public function manageQuestions($testId)
    {
        $test = Test::findOrFail($testId);
        $questions = $test->questions()->with('answers')->orderBy('order_index')->get();
        return view('admin.questions.manage', compact('test', 'questions'));
    }

    // Сохранение вопроса (редирект на manageQuestions)
    public function storeQuestion(Request $request, $testId)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:single_choice,multiple_choice,text_input,matching',
            'points' => 'required|integer|min:1',
            'answers' => 'required|array|min:2',
            'correct_answer' => 'required|array|min:1',
        ]);

        $question = Question::create([
            'test_id' => $testId,
            'question_text' => $validated['question_text'],
            'question_type' => $validated['question_type'],
            'points' => $validated['points'],
        ]);

        foreach ($validated['answers'] as $index => $text) {
            if (empty(trim($text))) continue;
            $isCorrect = in_array($index, $validated['correct_answer']);
            $question->answers()->create([
                'answer_text' => trim($text),
                'is_correct' => $isCorrect,
                'order_index' => $index,
            ]);
        }

        return redirect()->route('admin.questions.manage', $testId)->with('success', 'Вопрос добавлен');
    }

    // Удаление вопроса
    public function deleteQuestion($id)
    {
        $question = Question::findOrFail($id);
        $testId = $question->test_id;
        $question->delete();
        return redirect()->route('admin.questions.manage', $testId)->with('success', 'Вопрос удалён');
    }

    // Редактирование вопроса (форма)
    public function editQuestion($id)
    {
        $question = Question::with('answers')->findOrFail($id);
        return view('admin.questions.edit', compact('question'));
    }

    // Обновление вопроса
    public function updateQuestion(Request $request, $id)
    {
        $question = Question::findOrFail($id);
        $validated = $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:single_choice,multiple_choice,text_input,matching',
            'points' => 'required|integer|min:1',
            'answers' => 'required|array|min:2',
            'correct_answer' => 'required|array|min:1',
        ]);

        $question->update([
            'question_text' => $validated['question_text'],
            'question_type' => $validated['question_type'],
            'points' => $validated['points'],
        ]);

        $question->answers()->delete();
        foreach ($validated['answers'] as $index => $text) {
            if (empty(trim($text))) continue;
            $isCorrect = in_array($index, $validated['correct_answer']);
            $question->answers()->create([
                'answer_text' => trim($text),
                'is_correct' => $isCorrect,
                'order_index' => $index,
            ]);
        }

        return redirect()->route('admin.questions.manage', $question->test_id)->with('success', 'Вопрос обновлён');
    }

    // Экспорт CSV
    public function exportCsv()
    {
        $filename = "otchet_testy_" . date('Y-m-d') . ".csv";
        header('Content-Encoding: UTF-8');
        header('Content-type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename=' . $filename);
        echo "\xEF\xBB\xBF";
        $results = TestResult::with(['user', 'test.subject'])->orderBy('completed_at', 'desc')->get();
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Ученик', 'Тест', 'Предмет', 'Результат (%)', 'Дата'], ";");
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
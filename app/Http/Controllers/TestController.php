<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\DifficultyLevel;
use App\Models\Test;
use App\Models\Answer;
use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function selectLevel($subjectId)
    {
        $subject = Subject::findOrFail($subjectId);
        $levels = DifficultyLevel::all(); // без теории

        return view('tests.select-level', compact('subject', 'levels'));
    }

    // Новый метод: список тестов для предмета и класса
    public function listTests($subjectId, $difficultyId)
    {
        $subject = Subject::findOrFail($subjectId);
        $level = DifficultyLevel::findOrFail($difficultyId);
        $tests = Test::where('subject_id', $subjectId)
                     ->where('difficulty_id', $difficultyId)
                     ->where('is_active', true)
                     ->get();

        return view('tests.list', compact('subject', 'level', 'tests'));
    }

    // Новый метод: старт теста по ID
    public function startTestById($testId)
    {
        $test = Test::findOrFail($testId);
        $questions = $test->questions()->with('answers')->get();

        if ($questions->isEmpty()) {
            return redirect()->back()->with('error', 'В этом тесте пока нет вопросов.');
        }

        return view('tests.take-test', compact('test', 'questions'));
    }

    // Старый метод (можно оставить, но лучше не использовать)
    public function startTest($subjectId, $difficultyId)
    {
        $test = Test::where('subject_id', $subjectId)
                    ->where('difficulty_id', $difficultyId)
                    ->where('is_active', true)
                    ->firstOrFail();

        $questions = $test->questions()->with('answers')->get();

        if ($questions->isEmpty()) {
            return redirect()->back()->with('error', 'В этом тесте пока нет вопросов.');
        }

        return view('tests.take-test', compact('test', 'questions'));
    }

    public function submitTest(Request $request, $testId)
    {
        $test = Test::findOrFail($testId);
        $user = Auth::user();

        $answers = $request->input('answers', []);
        $totalQuestions = $test->questions()->count();
        $correctCount = 0;

        foreach ($answers as $questionId => $selectedAnswerId) {
            $correctAnswer = Answer::where('question_id', $questionId)
                                   ->where('is_correct', true)
                                   ->first();

            if ($correctAnswer && $correctAnswer->id == $selectedAnswerId) {
                $correctCount++;
            }
        }

        $percentage = $totalQuestions > 0 ? ($correctCount / $totalQuestions) * 100 : 0;
        $earnedPoints = $correctCount + $test->points_for_completion;
        $user->increment('points', $earnedPoints);

        TestResult::create([
            'user_id' => $user->id,
            'test_id' => $testId,
            'score' => $correctCount,
            'max_score' => $totalQuestions,
            'percentage' => round($percentage, 2),
        ]);

        return view('tests.result', compact('test', 'correctCount', 'totalQuestions', 'percentage', 'earnedPoints'));
    }
}
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
        $levels = DifficultyLevel::all();
        return view('tests.select-level', compact('subject', 'levels'));
    }

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

    public function startTestById($testId)
    {
        $test = Test::findOrFail($testId);
        $questions = $test->questions()->with('answers')->orderBy('order_index')->get();

        // Подготовка данных для вопросов типа "соответствие"
        foreach ($questions as $question) {
            if ($question->question_type == 'matching') {
                $pairs = [];
                $rightOptions = [];
                foreach ($question->answers as $answer) {
                    $parts = explode(' → ', $answer->answer_text);
                    if (count($parts) == 2) {
                        $pairs[] = ['left' => $parts[0], 'right' => $parts[1]];
                        $rightOptions[] = $parts[1];
                    }
                }
                $question->matching_pairs = $pairs;
                $question->right_options = array_unique($rightOptions);
            }
        }

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

        foreach ($test->questions as $question) {
            if ($question->question_type == 'matching') {
                // Получаем правильные соответствия
                $correctMap = [];
                foreach ($question->answers as $answer) {
                    $parts = explode(' → ', $answer->answer_text);
                    if (count($parts) == 2) {
                        $correctMap[$parts[0]] = $parts[1];
                    }
                }
                // Получаем ответы пользователя
                $userMatches = $answers[$question->id] ?? [];
                foreach ($correctMap as $left => $right) {
                    if (isset($userMatches[$left]) && $userMatches[$left] == $right) {
                        $correctCount++;
                    }
                }
            } else {
                $selected = $answers[$question->id] ?? null;
                if ($selected) {
                    if ($question->question_type == 'text_input') {
                        $correctAnswer = $question->answers->first()->answer_text;
                        if (strtolower(trim($selected)) == strtolower(trim($correctAnswer))) {
                            $correctCount++;
                        }
                    } else {
                        $correctAnswerIds = $question->answers->where('is_correct', true)->pluck('id')->toArray();
                        if ($question->question_type == 'single_choice') {
                            if ($selected == $correctAnswerIds[0]) {
                                $correctCount++;
                            }
                        } elseif ($question->question_type == 'multiple_choice') {
                            $selectedIds = is_array($selected) ? $selected : [$selected];
                            if (empty(array_diff($selectedIds, $correctAnswerIds)) && empty(array_diff($correctAnswerIds, $selectedIds))) {
                                $correctCount++;
                            }
                        }
                    }
                }
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
            'completed_at' => now(),
        ]);

        return view('tests.result', compact('test', 'correctCount', 'totalQuestions', 'percentage', 'earnedPoints'));
    }
}
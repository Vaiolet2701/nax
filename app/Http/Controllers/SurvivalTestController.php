<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SurvivalQuestion;
use App\Models\SurvivalTestResult;
use Illuminate\Support\Str;

class SurvivalTestController extends Controller
{
    public function showTest()
    {
        $questions = SurvivalQuestion::inRandomOrder()->limit(20)->get();
        return view('survival.test', compact('questions'));
    }

    public function submitTest(Request $request)
    {
        $questions = SurvivalQuestion::whereIn('id', array_keys($request->answers))->get();
        $score = 0;
        $results = [];

        foreach ($questions as $question) {
            $userAnswer = $request->answers[$question->id];
            $isCorrect = $userAnswer == $question->correct_option;
            
            if ($isCorrect) {
                $score++;
            }

            $results[] = [
                'question' => $question->question,
                'user_answer' => $question->options[$userAnswer],
                'correct_answer' => $question->options[$question->correct_option],
                'is_correct' => $isCorrect,
                'explanation' => $question->explanation,
            ];
        }

        $totalQuestions = count($questions);
        $percentage = ($score / $totalQuestions) * 100;

        // Сохраняем результат для авторизованных пользователей
        if (Auth::check()) {
            SurvivalTestResult::create([
                'user_id' => Auth::id(),
                'score' => $score,
                'total_questions' => $totalQuestions,
                'percentage' => $percentage,
            ]);
        } else {
            // Для неавторизованных сохраняем только в сессии
            SurvivalTestResult::create([
                'session_id' => session()->getId(),
                'score' => $score,
                'total_questions' => $totalQuestions,
                'percentage' => $percentage,
            ]);
        }

        return view('survival.results', [
            'score' => $score,
            'totalQuestions' => $totalQuestions,
            'percentage' => $percentage,
            'results' => $results,
            'isLoggedIn' => Auth::check(),
        ]);
    }

    public function showResults($id = null)
    {
        if ($id) {
            $result = SurvivalTestResult::findOrFail($id);
            
            // Проверяем, что пользователь имеет доступ к этому результату
            if ((Auth::check() && $result->user_id === Auth::id()) || 
                (!$result->user_id && $result->session_id === session()->getId())) {
                return view('survival.results_single', compact('result'));
            }
            
            abort(403);
        }

        // Для авторизованных пользователей показываем все их результаты
        if (Auth::check()) {
            $results = SurvivalTestResult::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Для неавторизованных показываем только текущий результат из сессии
            $results = SurvivalTestResult::where('session_id', session()->getId())
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('survival.results_history', compact('results'));
    }
}
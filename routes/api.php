<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Lesson Quiz API (public for enrolled students)
Route::get('/lesson/{lessonId}/quiz', function ($lessonId) {
    $lesson = \App\Models\Lesson::findOrFail($lessonId);
    $quiz = \App\Models\Quiz::where('lesson_id', $lessonId)->first();
    
    if (!$quiz) {
        return response()->json(['questions' => []]);
    }
    
    $questions = \App\Models\QuizQuestion::where('quiz_id', $quiz->id)->get()->map(function ($q) {
        return [
            'id' => $q->id,
            'question' => $q->question_text,
            'options' => $q->options ?? [],
            'correctAnswer' => $q->correct_answer,
        ];
    });
    
    return response()->json(['questions' => $questions]);
});

// Lesson AI Chat
Route::post('/lesson/{lessonId}/chat', [\App\Http\Controllers\LessonChatController::class, 'chat']);

// Featured Courses API (for homepage)
Route::get('/courses/featured', function () {
    $courses = \App\Models\Course::with(['instructor', 'lessons'])
        ->orderBy('created_at', 'desc')
        ->take(4)
        ->get()
        ->map(function ($course) {
            return [
                'id' => $course->id,
                'title' => $course->title,
                'instructor' => $course->instructor->name ?? 'Unknown Instructor',
                'image' => $course->thumbnail ?? 'https://images.unsplash.com/photo-1557324232-b8917d3c3dcb?w=800',
                'rating' => round($course->reviews()->avg('rating') ?? 4.5, 1),
                'students' => $course->enrollments()->count(),
                'lessons' => $course->lessons->count(),
                'duration' => $course->lessons->count() . ' lessons',
                'level' => $course->level ?? 'All Levels',
            ];
        });
    
    return response()->json($courses);
});

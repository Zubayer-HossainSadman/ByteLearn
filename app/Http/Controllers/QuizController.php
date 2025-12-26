<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Lesson;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    /**
     * Show quiz questions
     */
    public function show($quizId)
    {
        $quiz = Quiz::with('attempts', 'lesson.course')->findOrFail($quizId);
        $student = Auth::user();
        $course = $quiz->lesson->course;

        // Check enrollment
        $isEnrolled = $course->enrollments()
                            ->where('user_id', $student->id)
                            ->exists();

        if (!$isEnrolled && $course->instructor_id !== $student->id) {
            abort(403, 'Not enrolled in this course');
        }

        return view('student.quizzes.show', ['quiz' => $quiz]);
    }

    /**
     * Submit quiz answers
     */
    public function submit(Request $request, $quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $student = Auth::user();

        $score = $request->input('score', 0);
        $attempt = QuizAttempt::create([
            'quiz_id' => $quizId,
            'user_id' => $student->id,
            'score' => $score,
            'attempt_date' => now(),
        ]);

        return redirect()->route('student.quiz.result', $attempt->id)
                       ->with('success', 'Quiz submitted successfully!');
    }

    /**
     * Show quiz results
     */
    public function result($attemptId)
    {
        $attempt = QuizAttempt::with('quiz.lesson', 'user')->findOrFail($attemptId);
        $student = Auth::user();

        if ($attempt->user_id !== $student->id) {
            abort(403, 'Unauthorized');
        }

        return view('student.quizzes.result', ['attempt' => $attempt]);
    }

    /**
     * Create quiz (Instructor)
     */
    public function create($lessonId)
    {
        $instructor = Auth::user();
        $lesson = Lesson::with('course')->findOrFail($lessonId);

        if ($lesson->course->instructor_id !== $instructor->id) {
            abort(403, 'Unauthorized');
        }

        return view('instructor.quizzes.create', ['lesson' => $lesson]);
    }

    /**
     * Store new quiz
     */
    public function store(Request $request, $lessonId)
    {
        $instructor = Auth::user();
        $lesson = Lesson::with('course')->findOrFail($lessonId);

        if ($lesson->course->instructor_id !== $instructor->id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'ai_generated' => 'boolean',
        ]);

        $quiz = Quiz::create([
            'lesson_id' => $lessonId,
            'title' => $validated['title'],
            'ai_generated' => $validated['ai_generated'] ?? false,
        ]);

        return redirect()->route('instructor.quizzes.edit', $quiz->id)
                       ->with('success', 'Quiz created successfully!');
    }
}


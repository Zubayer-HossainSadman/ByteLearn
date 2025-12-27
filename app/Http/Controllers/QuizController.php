<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Lesson;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

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

    /**
     * Get quiz questions for a lesson (API)
     */
    public function getQuestions($lessonId)
    {
        $lesson = Lesson::with('course')->findOrFail($lessonId);
        
        // Get or create quiz for this lesson
        $quiz = Quiz::where('lesson_id', $lessonId)->first();
        
        if (!$quiz) {
            return response()->json(['questions' => []]);
        }

        $questions = $quiz->questions->map(function ($q) {
            return [
                'id' => $q->id,
                'question' => $q->question_text,
                'options' => $q->options ?? [],
                'correctAnswer' => $q->correct_answer,
            ];
        });

        return response()->json(['questions' => $questions]);
    }

    /**
     * Save quiz questions for a lesson (API)
     */
    public function saveQuestions(Request $request, $lessonId)
    {
        $instructor = Auth::user();
        $lesson = Lesson::with('course')->findOrFail($lessonId);

        if ($lesson->course->instructor_id !== $instructor->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'questions' => 'required|array|max:6',
            'questions.*.question' => 'required|string',
            'questions.*.options' => 'required|array|size:4',
            'questions.*.correctAnswer' => 'required|integer|min:0|max:3',
        ]);

        // Get or create quiz
        $quiz = Quiz::firstOrCreate(
            ['lesson_id' => $lessonId],
            ['title' => $lesson->title . ' Quiz', 'ai_generated' => false]
        );

        // Delete existing questions
        $quiz->questions()->delete();

        // Create new questions
        foreach ($validated['questions'] as $q) {
            QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question_text' => $q['question'],
                'question_type' => 'multiple_choice',
                'options' => $q['options'],
                'correct_answer' => $q['correctAnswer'],
            ]);
        }

        return response()->json(['message' => 'Quiz saved successfully!', 'quiz_id' => $quiz->id]);
    }

    /**
     * Generate quiz questions using Gemini AI (API)
     */
    public function generateWithAI(Request $request, $lessonId)
    {
        $instructor = Auth::user();
        $lesson = Lesson::with('course')->findOrFail($lessonId);

        if ($lesson->course->instructor_id !== $instructor->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $existingCount = $request->input('existingCount', 0);
        $toGenerate = min(6 - $existingCount, 6);

        if ($toGenerate <= 0) {
            return response()->json(['error' => 'Maximum 6 questions allowed'], 400);
        }

        // Build context
        $lessonTitle = $request->input('lessonTitle', $lesson->title);
        $lessonContent = $request->input('lessonContent', $lesson->content);
        $videoUrl = $request->input('videoUrl', $lesson->video_url);

        $context = "Lesson Title: " . $lessonTitle . "\n";
        if ($lessonContent) $context .= "Lesson Content: " . strip_tags($lessonContent) . "\n";
        if ($videoUrl) $context .= "Video Reference: " . $videoUrl . "\n";

        if (strlen($context) < 30) {
            return response()->json([
                'error' => 'Not enough lesson content to generate questions. Please add title, content, or video.'
            ], 400);
        }

        $prompt = "Based on the content below, generate exactly {$toGenerate} multiple choice quiz questions.
Each question must have 4 options (A, B, C, D) and one correct answer index (0-3).
Return ONLY valid JSON array. No markdown, no explanations.

Context:
{$context}

Format:
[
  {
    \"question\": \"Question text?\",
    \"options\": [\"A\", \"B\", \"C\", \"D\"],
    \"correctAnswer\": 0
  }
]";

        $apiKey = config('ai.openrouter.api_key');
        $model = config('ai.openrouter.model');
        $siteUrl = config('ai.openrouter.site_url');
        $siteName = config('ai.openrouter.site_name');

        $maxRetries = 3;
        $retryDelay = 2; // seconds

        for ($attempt = 0; $attempt < $maxRetries; $attempt++) {
            try {
                $response = Http::timeout(60)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $apiKey,
                        'Content-Type' => 'application/json',
                        'HTTP-Referer' => $siteUrl,
                        'X-Title' => $siteName,
                    ])
                    ->post('https://openrouter.ai/api/v1/chat/completions', [
                        'model' => $model,
                        'messages' => [
                            ['role' => 'user', 'content' => $prompt]
                        ],
                        'max_tokens' => 2048,
                        'temperature' => 0.7,
                    ]);

                if ($response->status() === 429) {
                    \Log::warning("OpenRouter Rate Limit hit. Retrying attempt " . ($attempt + 1));
                    sleep($retryDelay * ($attempt + 1));
                    continue;
                }

                if (!$response->successful()) {
                    \Log::error('OpenRouter API Error', ['status' => $response->status(), 'body' => $response->body()]);
                    return response()->json([
                        'error' => 'OpenRouter Error',
                        'details' => $response->body(),
                        'status' => $response->status()
                    ], $response->status());
                }

                $data = $response->json();
                $text = $data['choices'][0]['message']['content'] ?? '';

                // Clean response text (remove markdown code blocks if any)
                $text = preg_replace('/^```json\s*|\s*```$/', '', trim($text));
                
                // Extract JSON
                preg_match('/\[[\s\S]*\]/', $text, $matches);
                $jsonStr = $matches[0] ?? '[]';
                $questions = json_decode($jsonStr, true);

                if (!$questions || !is_array($questions)) {
                    // If parsing fails, retry might help if model gave bad output
                    if ($attempt < $maxRetries - 1) continue;
                    return response()->json(['error' => 'Failed to parse AI response. Try again.'], 500);
                }

                return response()->json(['questions' => $questions]);

            } catch (\Exception $e) {
                \Log::error('AI Generation Exception: ' . $e->getMessage());
                if ($attempt < $maxRetries - 1) {
                    sleep($retryDelay);
                    continue;
                }
                return response()->json(['error' => 'AI generation failed'], 500);
            }
        }

        return response()->json(['error' => 'AI service busy. Please try again later.'], 503);
    }
}

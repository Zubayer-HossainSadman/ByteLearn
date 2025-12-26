<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\InstructorDashboardController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\ReviewController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication Routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Course Discovery Routes (Public/Students)
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
// React Views (Hybrid)
Route::get('/courses/{id}', [CourseController::class, 'show'])->name('courses.show');
Route::get('/courses/{id}/edit', [CourseController::class, 'edit'])->name('courses.edit')->middleware('auth');
Route::get('/courses/{id}/learn/lessons/{lessonId?}', [CourseController::class, 'learn'])->name('courses.learn')->middleware('auth');

// Student Routes
Route::prefix('student')
    ->middleware(['auth', 'role:student'])
    ->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
        Route::get('/courses', [StudentDashboardController::class, 'courses'])->name('student.courses');
        Route::get('/completed', [StudentDashboardController::class, 'completedCourses'])->name('student.completed-courses');
        Route::get('/continue/{courseId}', [StudentDashboardController::class, 'continueLearning'])->name('student.continue-learning');

        // Enrollment
        Route::post('/enroll/{courseId}', [EnrollmentController::class, 'store'])->name('student.enroll');
        Route::delete('/unenroll/{enrollmentId}', [EnrollmentController::class, 'destroy'])->name('student.unenroll');

        // Lessons
        Route::get('/lesson/{id}', [LessonController::class, 'view'])->name('student.lesson.view');
        Route::post('/lesson/{id}/complete', [LessonController::class, 'markComplete'])->name('student.lesson.complete');

        // Quizzes
        Route::get('/quiz/{quizId}', [QuizController::class, 'show'])->name('student.quiz.show');
        Route::post('/quiz/{quizId}/submit', [QuizController::class, 'submit'])->name('student.quiz.submit');
        Route::get('/quiz/result/{attemptId}', [QuizController::class, 'result'])->name('student.quiz.result');

        // Discussions
        Route::get('/lesson/{lessonId}/discussions', [DiscussionController::class, 'index'])->name('student.discussions.index');
        Route::post('/lesson/{lessonId}/discussion', [DiscussionController::class, 'store'])->name('student.discussion.store');
        Route::get('/discussion/{discussionId}', [DiscussionController::class, 'show'])->name('student.discussion.show');
        Route::post('/discussion/{discussionId}/reply', [DiscussionController::class, 'reply'])->name('student.discussion.reply');

        // Chatbot
        Route::post('/chat/message', [ChatbotController::class, 'sendMessage'])->name('student.chat.message');
        Route::get('/chat/{courseId}/history', [ChatbotController::class, 'history'])->name('student.chat.history');

        // Certificates
        Route::get('/certificates', [CertificateController::class, 'index'])->name('student.certificates.index');
        Route::post('/certificate/{courseId}/generate', [CertificateController::class, 'generate'])->name('student.certificate.generate');
        Route::get('/certificate/{certificateId}/download', [CertificateController::class, 'download'])->name('student.certificate.download');
        
        // Course Completion
        Route::post('/course/{courseId}/complete', [CertificateController::class, 'completeCourse'])->name('student.course.complete');
        
        // Course Rating
        Route::post('/course/{courseId}/review', [ReviewController::class, 'store'])->name('student.course.review');
    });

// Instructor Routes
Route::prefix('instructor')
    ->middleware(['auth', 'role:instructor'])
    ->group(function () {
        Route::get('/dashboard', [InstructorDashboardController::class, 'index'])->name('instructor.dashboard');
        Route::get('/courses', [InstructorDashboardController::class, 'courses'])->name('instructor.courses');
        Route::get('/course/{courseId}/analytics', [InstructorDashboardController::class, 'courseAnalytics'])->name('instructor.course.analytics');

        // Course Management
        Route::get('/courses/create', [CourseController::class, 'create'])->name('instructor.courses.create');
        Route::post('/courses', [CourseController::class, 'store'])->name('instructor.courses.store');
        Route::get('/courses/{id}/edit', [CourseController::class, 'edit'])->name('instructor.courses.edit');
        Route::patch('/courses/{id}', [CourseController::class, 'update'])->name('instructor.courses.update');
        Route::post('/courses/{id}/publish', [CourseController::class, 'togglePublish'])->name('instructor.courses.publish');
        Route::delete('/courses/{id}', [CourseController::class, 'destroy'])->name('instructor.courses.destroy');

        // Lesson Management
        Route::get('/courses/{courseId}/lesson/create', [LessonController::class, 'create'])->name('instructor.lessons.create');
        Route::post('/courses/{courseId}/lesson', [LessonController::class, 'store'])->name('instructor.lessons.store');

        // Quiz Management
        Route::get('/lesson/{lessonId}/quiz/create', [QuizController::class, 'create'])->name('instructor.quizzes.create');
        Route::post('/lesson/{lessonId}/quiz', [QuizController::class, 'store'])->name('instructor.quizzes.store');
    });

// API Routes (Optional - for AJAX requests)
Route::prefix('api')->group(function () {
    Route::post('/chat/message', [ChatbotController::class, 'sendMessage']);
    Route::post('/lesson/complete', [LessonController::class, 'markComplete']);
});
// API Routes for React Frontend
Route::middleware(['auth'])->prefix('api')->group(function () {
    // Course Catalog
    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/categories', [CourseController::class, 'categories']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);

    // Instructor Course Management
    Route::get('/instructor/courses', [CourseController::class, 'instructorCourses']);
    Route::post('/courses', [CourseController::class, 'store']);
    Route::put('/courses/{id}', [CourseController::class, 'update']);
    Route::post('/courses/{id}/toggle-status', [CourseController::class, 'toggleStatus']);
    Route::delete('/courses/{id}', [CourseController::class, 'destroy']);

    // Lesson Management
    Route::post('/lessons', [LessonController::class, 'store']);
    Route::post('/lessons/reorder', [LessonController::class, 'reorder']);
    Route::put('/lessons/{id}', [LessonController::class, 'update']);
    Route::delete('/lessons/{id}', [LessonController::class, 'destroy']);

    // Enrollment Progress
    Route::post('/enrollments/progress', [EnrollmentController::class, 'updateProgress']);
});

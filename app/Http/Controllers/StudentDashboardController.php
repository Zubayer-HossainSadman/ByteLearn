<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    /**
     * Show student dashboard
     */
    public function index()
    {
        $student = Auth::user();

        // Get enrolled courses
        $enrolledCourses = $student->enrollments()->with('course')->get();
        
        $courseProgress = [];
        foreach ($enrolledCourses as $enrollment) {
            $courseProgress[$enrollment->course_id] = $enrollment->progress ?? 0;
        }

        // Calculate stats
        $ongoingCourses = $enrolledCourses->count();
        $completedCourses = $student->certificates()->count();
        $learningStreak = $student->learning_streak ?? 0;
        $certificatesEarned = $completedCourses;
        
        // Get notifications - safely handle if table doesn't exist yet
        $notifications = collect([]);
        try {
            $notifications = $student->notifications()
                                     ->orderBy('created_at', 'desc')
                                     ->limit(10)
                                     ->get();
        } catch (\Exception $e) {
            // Notifications table doesn't exist yet - return empty collection
            $notifications = collect([]);
        }

        return view('student.dashboard', [
            'student' => $student,
            'enrolledCourses' => $enrolledCourses,
            'courseProgress' => $courseProgress,
            'ongoingCourses' => $ongoingCourses,
            'completedCourses' => $completedCourses,
            'learningStreak' => $learningStreak,
            'certificatesEarned' => $certificatesEarned,
            'notifications' => $notifications,
        ]);
    }

    /**
     * Show enrolled courses
     */
    public function courses()
    {
        $student = Auth::user();
        $enrolledCourses = $student->enrollments()->with('course')->paginate(10);

        return view('student.courses', ['enrolledCourses' => $enrolledCourses]);
    }

    /**
     * Show completed courses
     */
    public function completedCourses()
    {
        $student = Auth::user();
        $completedCourses = $student->certificates()->with('course')->paginate(10);

        return view('student.completed-courses', ['completedCourses' => $completedCourses]);
    }

    /**
     * Continue learning - get next uncompleted lesson
     */
    public function continueLearning($courseId)
    {
        $student = Auth::user();
        $course = Course::findOrFail($courseId);

        // Check if student is enrolled
        $enrollment = Enrollment::where('user_id', $student->id)
                                 ->where('course_id', $courseId)
                                 ->firstOrFail();

        // Get first lesson
        $nextLesson = $course->lessons()->orderBy('sequence_number')->first();

        if (!$nextLesson) {
            return redirect()->route('student.courses')
                           ->with('info', 'No lessons available in this course!');
        }

        return redirect()->route('student.lesson.view', $nextLesson->id);
    }
}


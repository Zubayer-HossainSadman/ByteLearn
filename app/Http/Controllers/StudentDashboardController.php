<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
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

        // Get leaderboard - top students by points
        $leaderboard = User::where('role', 'student')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'points' => $user->getLeaderboardPoints(),
                    'streak' => $user->learning_streak ?? 0,
                    'lessonsCompleted' => $user->getTotalLessonsCompleted(),
                ];
            })
            ->sortByDesc('points')
            ->take(10)
            ->values();

        // Format Completed Courses Data for React
        $learningStreak = $student->learning_streak ?? 0;
        $certificatesEarned = $completedCourses;
        
        $completedCoursesData = $student->certificates()->with(['course.reviews' => function($query) use ($student) {
            $query->where('user_id', $student->id);
        }])->get()->map(function($cert) {
            $userReview = $cert->course->reviews->first();
            return [
                'id' => $cert->course->id,
                'title' => $cert->course->title,
                'instructor' => $cert->course->instructor->name ?? 'Instructor',
                'completedDate' => $cert->created_at->format('M d, Y'),
                'rating' => $userReview ? $userReview->rating : 0,
                'certificate' => true,
                'certificateId' => $cert->id
            ];
        });

        return view('student.dashboard', [
            'student' => $student,
            'enrolledCourses' => $enrolledCourses,
            'courseProgress' => $courseProgress,
            'ongoingCourses' => $ongoingCourses,
            'completedCourses' => $completedCourses,
            'learningStreak' => $learningStreak,
            'certificatesEarned' => $certificatesEarned,
            'notifications' => $notifications,
            'leaderboard' => $leaderboard,
            'currentUserPoints' => $student->getLeaderboardPoints(),
            'completedCoursesData' => $completedCoursesData
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


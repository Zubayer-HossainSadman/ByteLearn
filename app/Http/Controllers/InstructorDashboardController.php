<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Enrollment;
use App\Models\Progress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstructorDashboardController extends Controller
{
    /**
     * Show instructor dashboard
     */
    public function index()
    {
        $instructor = Auth::user();

        $courses = $instructor->courses()->with('lessons', 'enrollments')->get();
        
        $totalCourses = $courses->count();
        
        // Count total students across all courses
        $totalStudents = $courses->sum(function($course) {
            return $course->enrollments->count();
        });
        
        // Count total lessons across all courses
        $totalLessons = $courses->sum(function($course) {
            return $course->lessons->count();
        });

        // Calculate Average Rating across all courses
        $totalReviews = $courses->sum(function($course) {
            return $course->reviews->count();
        });
        
        $sumRatings = $courses->sum(function($course) {
            return $course->reviews->sum('rating');
        });
        
        $averageRating = $totalReviews > 0 ? round($sumRatings / $totalReviews, 1) : 0;

        return view('instructor.dashboard', [
            'instructor' => $instructor,
            'courses' => $courses,
            'totalCourses' => $totalCourses,
            'totalStudents' => $totalStudents,
            'totalLessons' => $totalLessons,
            'averageRating' => $averageRating,
        ]);
    }

    /**
     * Show all instructor courses
     */
    public function courses()
    {
        $instructor = Auth::user();
        $courses = $instructor->courses()->with('lessons', 'enrollments')->paginate(10);

        return view('instructor.courses', ['courses' => $courses]);
    }

    /**
     * Show course analytics
     */
    public function courseAnalytics($courseId)
    {
        $instructor = Auth::user();
        $course = Course::where('instructor_id', $instructor->id)
            ->with(['enrollments.user', 'reviews.user'])
            ->findOrFail($courseId);

        // Total Enrollments
        $totalEnrollments = $course->enrollments->count();

        // Enrollments This Week
        $enrollmentsThisWeek = $course->enrollments()
            ->where('created_at', '>=', now()->subWeek())
            ->count();

        // Course Rating Stats
        $reviews = $course->reviews()->with('user')->orderBy('created_at', 'desc')->get();
        $averageRating = $reviews->avg('rating') ?? 0;
        $totalReviews = $reviews->count();

        // Rating Distribution (1-5 stars)
        $ratingDistribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $ratingDistribution[$i] = $reviews->where('rating', $i)->count();
        }

        return view('instructor.course-analytics', [
            'course' => $course,
            'totalEnrollments' => $totalEnrollments,
            'enrollmentsThisWeek' => $enrollmentsThisWeek,
            'averageRating' => round($averageRating, 1),
            'totalReviews' => $totalReviews,
            'ratingDistribution' => $ratingDistribution,
            'reviews' => $reviews,
        ]);
    }
}

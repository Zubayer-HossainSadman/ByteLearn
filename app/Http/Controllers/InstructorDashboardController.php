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

        return view('instructor.dashboard', [
            'instructor' => $instructor,
            'courses' => $courses,
            'totalCourses' => $totalCourses,
            'totalStudents' => $totalStudents,
            'totalLessons' => $totalLessons,
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
        $course = Course::where('instructor_id', $instructor->id)->findOrFail($courseId);

        $enrollments = $course->enrollments()->with('user')->get();

        $totalStudents = $enrollments->count();
        $completedCourses = 0;
        foreach ($enrollments as $enrollment) {
            if ($enrollment->isCompleted()) {
                $completedCourses++;
            }
        }

        return view('instructor.course-analytics', [
            'course' => $course,
            'enrollments' => $enrollments,
            'totalStudents' => $totalStudents,
            'completedCourses' => $completedCourses,
            'completionRate' => $totalStudents > 0 ? round(($completedCourses / $totalStudents) * 100, 2) : 0,
        ]);
    }
}

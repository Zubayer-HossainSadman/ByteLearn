

<?php $__env->startSection('title', 'Instructor Dashboard - ByteLearn'); ?>

<?php $__env->startSection('scripts'); ?>
<?php
    $userData = [
        'id' => $instructor->id ?? auth()->user()->id,
        'name' => $instructor->name ?? auth()->user()->name,
        'email' => $instructor->email ?? auth()->user()->email,
        'role' => 'instructor'
    ];

    $coursesData = $courses->map(function($course) {
        return [
            'id' => $course->id,
            'title' => $course->title,
            'image' => 'https://images.unsplash.com/photo-1557324232-b8917d3c3dcb?w=600',
            'students' => $course->enrollments->count(),
            'status' => ucfirst($course->status),
            'completionRate' => 0,
            'rating' => 4.5,
            'reviews' => 0,
            'revenue' => '$0',
            'lessons' => $course->lessons->count(),
            'lastUpdated' => $course->updated_at->diffForHumans()
        ];
    });
?>
<script>
    // Pass Laravel data to React
    document.getElementById('app-data').textContent = JSON.stringify({
        page: 'instructor-dashboard',
        user: <?php echo json_encode($userData, 15, 512) ?>,
        dashboardData: {
            courses: <?php echo json_encode($coursesData, 15, 512) ?>,
            stats: {
                'totalStudents': <?php echo e($totalStudents); ?>,
                'totalCourses': <?php echo e($totalCourses); ?>,
                'totalLessons': <?php echo e($totalLessons); ?>,
                'avgRating': 4.8
            }
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\DELL\PycharmProjects\PythonProject2\BT2\ByteLearn\resources\views/instructor/dashboard.blade.php ENDPATH**/ ?>
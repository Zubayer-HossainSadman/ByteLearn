

<?php $__env->startSection('title', 'Student Dashboard - ByteLearn'); ?>

<?php $__env->startSection('scripts'); ?>
<?php
    $userData = [
        'id' => $student->id ?? auth()->user()->id,
        'name' => $student->name ?? auth()->user()->name,
        'email' => $student->email ?? auth()->user()->email,
        'role' => 'student'
    ];

    $enrolledCoursesData = $enrolledCourses->map(function($enrollment) use ($courseProgress) {
        return [
            'id' => $enrollment->course->id,
            'title' => $enrollment->course->title,
            'instructor' => $enrollment->course->instructor->name ?? 'Instructor',
            'image' => 'https://images.unsplash.com/photo-1557324232-b8917d3c3dcb?w=600',
            'progress' => $courseProgress[$enrollment->course->id] ?? 0,
            'currentLesson' => 'Continue Learning',
            'totalLessons' => $enrollment->course->lessons->count(),
            'completedLessons' => round(($courseProgress[$enrollment->course->id] ?? 0) * $enrollment->course->lessons->count() / 100),
            'nextQuiz' => null,
            'lastAccessed' => ($enrollment->updated_at ?? $enrollment->enrollment_date ?? now())->diffForHumans()
        ];
    });

    $notificationsData = $notifications->map(function($notification) {
        return [
            'id' => $notification->id,
            'type' => 'info',
            'message' => $notification->message,
            'time' => $notification->created_at->diffForHumans(),
            'unread' => $notification->read_at === null
        ];
    });
?>
<script>
    // Pass Laravel data to React
    document.getElementById('app-data').textContent = JSON.stringify({
        page: 'student-dashboard',
        user: <?php echo json_encode($userData, 15, 512) ?>,
        dashboardData: {
            enrolledCourses: <?php echo json_encode($enrolledCoursesData, 15, 512) ?>,
            completedCourses: [],
            notifications: <?php echo json_encode($notificationsData, 15, 512) ?>,
            stats: {
                'ongoingCourses': <?php echo e($ongoingCourses); ?>,
                'completedCourses': <?php echo e($completedCourses); ?>,
                'learningStreak': <?php echo e($learningStreak); ?>,
                'certificatesEarned': <?php echo e($certificatesEarned); ?>

            },
            courseProgress: <?php echo json_encode($courseProgress, 15, 512) ?>
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\DELL\PycharmProjects\PythonProject2\BT2\ByteLearn\resources\views/student/dashboard.blade.php ENDPATH**/ ?>
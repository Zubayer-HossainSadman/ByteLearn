

<?php $__env->startSection('title', 'Course Analytics - ' . $course->title); ?>

<?php $__env->startSection('scripts'); ?>
<?php
    $userData = [
        'id' => auth()->user()->id,
        'name' => auth()->user()->name,
        'email' => auth()->user()->email,
        'role' => 'instructor'
    ];

    $reviewsData = $reviews->map(function($review) {
        return [
            'id' => $review->id,
            'rating' => $review->rating,
            'comment' => $review->comment,
            'userName' => $review->user->name ?? 'Anonymous',
            'createdAt' => $review->created_at->diffForHumans(),
        ];
    });
?>
<script>
    document.getElementById('app-data').textContent = JSON.stringify({
        page: 'instructor-analytics',
        user: <?php echo json_encode($userData, 15, 512) ?>,
        analyticsData: {
            courseId: <?php echo e($course->id); ?>,
            courseTitle: <?php echo json_encode($course->title, 15, 512) ?>,
            totalEnrollments: <?php echo e($totalEnrollments); ?>,
            enrollmentsThisWeek: <?php echo e($enrollmentsThisWeek); ?>,
            averageRating: <?php echo e($averageRating); ?>,
            totalReviews: <?php echo e($totalReviews); ?>,
            ratingDistribution: <?php echo json_encode($ratingDistribution, 15, 512) ?>,
            reviews: <?php echo json_encode($reviewsData, 15, 512) ?>
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\DELL\PycharmProjects\PythonProject2\BT2\ByteLearn\resources\views/instructor/course-analytics.blade.php ENDPATH**/ ?>
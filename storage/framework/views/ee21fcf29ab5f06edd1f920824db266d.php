

<?php $__env->startSection('styles'); ?>
<style>
    .welcome-banner {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 10px;
        margin-bottom: 2rem;
    }

    .dashboard-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .stat-card h3 {
        font-size: 2rem;
        color: #667eea;
        margin-bottom: 0.5rem;
    }

    .courses-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .course-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        transition: transform 0.3s;
        padding: 1.5rem;
    }

    .course-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
    }

    .course-card h3 {
        margin: 0 0 0.5rem 0;
        font-size: 1.25rem;
        color: #333;
    }

    .btn {
        display: inline-block;
        padding: 0.5rem 1rem;
        background: #667eea;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        border: none;
        cursor: pointer;
        font-size: 0.875rem;
    }

    .btn:hover {
        background: #764ba2;
    }

    .empty-state {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        text-align: center;
        color: #6b7280;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div style="max-width: 1400px; margin: 0 auto; padding: 2rem;">
    <div class="welcome-banner">
        <h1>Welcome back, <?php echo e($instructor->name ?? 'Instructor'); ?>!</h1>
        <p>Manage your courses and track student progress</p>
    </div>

    <div class="dashboard-stats">
        <div class="stat-card">
            <h3><?php echo e($totalCourses); ?></h3>
            <p>Total Courses</p>
        </div>
        <div class="stat-card">
            <h3><?php echo e($totalStudents); ?></h3>
            <p>Total Students</p>
        </div>
        <div class="stat-card">
            <h3><?php echo e($totalLessons); ?></h3>
            <p>Total Lessons</p>
        </div>
    </div>

    <section style="margin-top: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.5rem; margin: 0;">My Courses</h2>
            <a href="<?php echo e(route('instructor.courses.create')); ?>" class="btn">Create Course</a>
        </div>

        <?php if($courses->count() > 0): ?>
            <div class="courses-grid">
                <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="course-card">
                        <h3><?php echo e($course->title); ?></h3>
                        <p style="color: #6b7280; margin: 0.5rem 0;"><?php echo e($course->description ?? 'No description'); ?></p>
                        <div style="margin-top: 1rem; font-size: 0.875rem; color: #6b7280;">
                            <p>📚 <?php echo e($course->lessons->count()); ?> Lessons</p>
                            <p>👥 <?php echo e($course->enrollments->count()); ?> Students</p>
                        </div>
                        <div style="margin-top: 1rem;">
                            <a href="<?php echo e(route('instructor.course.analytics', $course->id)); ?>" class="btn">View Analytics</a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <p>You haven't created any courses yet.</p>
                <a href="<?php echo e(route('instructor.courses.create')); ?>" class="btn" style="margin-top: 1rem; display: inline-block;">Create Your First Course</a>
            </div>
        <?php endif; ?>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\DELL\PycharmProjects\PythonProject2\ByteLearn\resources\views/instructor/dashboard.blade.php ENDPATH**/ ?>
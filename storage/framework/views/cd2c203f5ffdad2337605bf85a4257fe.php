

<?php $__env->startSection('content'); ?>
<div class="search-section" style="background-color: white; padding: 2rem; margin-bottom: 2rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
    <h2>Browse Courses</h2>
    <form action="<?php echo e(route('courses.index')); ?>" method="GET" style="display: flex; gap: 1rem; margin-top: 1.5rem;">
        <input type="text" name="search" placeholder="Search courses..." value="<?php echo e(request('search')); ?>" style="flex: 1;">
        <select name="category">
            <option value="">All Categories</option>
            <option value="programming" <?php if(request('category') === 'programming'): echo 'selected'; endif; ?>>Programming</option>
            <option value="design" <?php if(request('category') === 'design'): echo 'selected'; endif; ?>>Design</option>
            <option value="business" <?php if(request('category') === 'business'): echo 'selected'; endif; ?>>Business</option>
            <option value="marketing" <?php if(request('category') === 'marketing'): echo 'selected'; endif; ?>>Marketing</option>
        </select>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
</div>

<?php if($courses->count()): ?>
    <div class="courses-grid">
        <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('courses.show', $course->id)); ?>" class="course-card" style="text-decoration: none;">
                <div class="course-card-thumbnail">📚</div>
                <div class="course-card-content">
                    <div class="course-card-title"><?php echo e($course->title); ?></div>
                    <div class="course-card-instructor">by <?php echo e($course->instructor->name); ?></div>
                    <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 1rem;">
                        <?php echo e(Str::limit($course->description, 80)); ?>

                    </p>
                    <div class="course-card-footer" style="border-top: 1px solid #e5e7eb; padding-top: 1rem; margin: 0 -1.5rem -1.5rem -1.5rem; padding: 1rem 1.5rem;">
                        <span style="font-size: 0.875rem; color: #6b7280;">
                            <?php echo e($course->lessons->count()); ?> Lessons • <?php echo e($course->enrollments->count()); ?> Students
                        </span>
                    </div>
                </div>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div style="margin-top: 2rem;">
        <?php echo e($courses->links()); ?>

    </div>
<?php else: ?>
    <div class="card" style="text-align: center; padding: 3rem;">
        <p style="color: #6b7280;">No courses found. Please try a different search.</p>
    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\DELL\PycharmProjects\PythonProject2\ByteLearn\resources\views/courses/index.blade.php ENDPATH**/ ?>
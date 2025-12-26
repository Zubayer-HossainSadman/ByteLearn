<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-brand">
            <a href="<?php echo e(route('home')); ?>" class="logo">ByteLearn</a>
        </div>

        <div class="navbar-menu">
            <a href="<?php echo e(route('courses.index')); ?>" class="nav-link">Courses</a>

            <?php if(auth()->guard()->check()): ?>
                <?php if(auth()->user()->role === 'student'): ?>
                    <a href="<?php echo e(route('student.dashboard')); ?>" class="nav-link">Dashboard</a>
                    <a href="<?php echo e(route('student.courses')); ?>" class="nav-link">My Courses</a>
                    
                    <!-- Notification Bell for Students -->
                    <button class="notification-bell" id="notificationBell" style="display: flex; align-items: center; gap: 0.5rem; position: relative;">
                        🔔
                        <?php
                            try {
                                $unreadCount = auth()->user()->notifications()
                                                               ->whereNull('read_at')
                                                               ->count();
                            } catch (\Exception $e) {
                                $unreadCount = 0;
                            }
                        ?>
                        <?php if($unreadCount > 0): ?>
                            <span class="notification-badge"><?php echo e($unreadCount > 9 ? '9+' : $unreadCount); ?></span>
                        <?php endif; ?>
                    </button>
                <?php elseif(auth()->user()->role === 'instructor'): ?>
                    <a href="<?php echo e(route('instructor.dashboard')); ?>" class="nav-link">Dashboard</a>
                    <a href="<?php echo e(route('instructor.courses')); ?>" class="nav-link">My Courses</a>
                <?php endif; ?>

                <div class="nav-user">
                    <span class="user-name"><?php echo e(auth()->user()->name); ?></span>
                    <form action="<?php echo e(route('logout')); ?>" method="POST" style="display:inline;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-secondary">Logout</button>
                    </form>
                </div>
            <?php else: ?>
                <a href="<?php echo e(route('login')); ?>" class="btn btn-primary">Login</a>
                <a href="<?php echo e(route('register')); ?>" class="btn btn-secondary">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<?php /**PATH C:\Users\DELL\PycharmProjects\PythonProject2\ByteLearn\resources\views/layouts/navbar.blade.php ENDPATH**/ ?>
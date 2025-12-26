<footer class="footer">
    <div class="footer-container">
        <div class="footer-section">
            <h3>ByteLearn</h3>
            <p>A peer-led micro learning platform for interactive education.</p>
        </div>

        <div class="footer-section">
            <h4>Quick Links</h4>
            <ul>
                <li><a href="<?php echo e(route('courses.index')); ?>">Browse Courses</a></li>
                <li><a href="<?php echo e(route('home')); ?>">Home</a></li>
            </ul>
        </div>

        <div class="footer-section">
            <h4>Account</h4>
            <ul>
                <?php if(auth()->guard()->check()): ?>
                    <li><a href="#">Profile</a></li>
                    <li><a href="#">Settings</a></li>
                <?php else: ?>
                    <li><a href="<?php echo e(route('login')); ?>">Login</a></li>
                    <li><a href="<?php echo e(route('register')); ?>">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; 2024 ByteLearn. All rights reserved.</p>
    </div>
</footer>
<?php /**PATH C:\Users\DELL\PycharmProjects\PythonProject2\ByteLearn\resources\views/layouts/footer.blade.php ENDPATH**/ ?>


<?php $__env->startSection('title', 'Login - ByteLearn'); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // Override initial data for login page
    document.getElementById('app-data').textContent = JSON.stringify({
        page: 'login',
        user: null
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\DELL\PycharmProjects\PythonProject2\BT2\ByteLearn\resources\views/auth/login.blade.php ENDPATH**/ ?>
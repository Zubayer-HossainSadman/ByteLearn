

<?php $__env->startSection('title', 'Register - ByteLearn'); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // Override initial data for register page
    document.getElementById('app-data').textContent = JSON.stringify({
        page: 'register',
        user: null,
        errors: <?php echo json_encode($errors->all(), 15, 512) ?>,
        old: <?php echo json_encode(session()->getOldInput(), 15, 512) ?>
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\DELL\PycharmProjects\PythonProject2\BT2\ByteLearn\resources\views/auth/register.blade.php ENDPATH**/ ?>
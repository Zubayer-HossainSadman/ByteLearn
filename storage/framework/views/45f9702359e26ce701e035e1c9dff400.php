

<?php $__env->startSection('title', 'Edit Course - ByteLearn'); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // Pass Laravel data to React for Course Editor
    document.getElementById('app-data').textContent = JSON.stringify(<?php echo json_encode($data, 15, 512) ?>);
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\DELL\PycharmProjects\PythonProject2\BT2\ByteLearn\resources\views/instructor/course/edit.blade.php ENDPATH**/ ?>
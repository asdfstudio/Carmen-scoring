<?php $__env->startSection('content-header'); ?>
  <h1>Update My Profile</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

  <p>
    Need to update your password? <?php echo e(link_to_route('password.edit', 'Update your password')); ?>

  </p>

  <?php echo form($form); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('body-footer'); ?>
  <script>let getNewUsernameURL = '<?php echo e(route('admin.user.username.new')); ?>'</script>
  <script src="/js/user-person-form.js"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->startSection('content-header'); ?>
  <?php if(Auth::user()->id == $user->id): ?>
    <h1>Update My Password</h1>
  <?php else: ?>
    <h1>Update <?php echo e($user->person->first_name); ?>'s Password</h1>

    <?php echo e(link_to(URL::previous(),'Back to previous page', ['class' => 'action'])); ?>

  <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


  <?php if(Auth::user()->id == $user->id): ?>
    <p>
      Need to update your email address or name? <?php echo e(link_to_route('profile.edit', 'Update your profile')); ?>

    </p>
  <?php endif; ?>

  <?php echo form($form); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
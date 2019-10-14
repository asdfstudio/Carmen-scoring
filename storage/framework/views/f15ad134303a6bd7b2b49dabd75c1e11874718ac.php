<?php $__env->startSection('content'); ?>

		<h1>User Details</h1>

		<h2><?php echo e($user->email); ?> - # <?php echo e($user->id); ?></h2>


    <h3>Delete User</h3>

    <?php echo form($deleteUserForm); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
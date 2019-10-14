<?php $__env->startSection('breadcrumbs'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content-header'); ?>
	<h1>Create a User</h1>

	<?php echo e(link_to_route('admin.user.index', 'Back to users', [], ['class' => 'action'])); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

		<?php echo form($form); ?>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('body-footer'); ?>
  <script>let getNewUsernameURL = '<?php echo e(route('admin.user.username.new')); ?>'</script>
  <script src="/js/user-person-form.js"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
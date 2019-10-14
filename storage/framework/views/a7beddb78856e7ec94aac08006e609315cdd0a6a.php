<?php $__env->startSection('title'); ?>
  Edit <?php echo e($competition->name); ?> | ##parent-placeholder-3c6de1b7dd91465d437ef415f94f36afc1fbc8a8##
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumbs'); ?>
	<?php echo Breadcrumbs::render('organizer.competition.edit', $competition); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

		<h1>Edit competition</h1>

		<?php echo form($form); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->startSection('breadcrumb'); ?>
	<?php echo Breadcrumbs::render('organizer.competition.division.index',$competition); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content-header'); ?>
	<h1>Manage Divisions</h1>

	<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('createDivision', [$competition])): ?>
		<?php echo e(link_to_route('organizer.competition.division.create', 'Add a division', [$competition], ['class' => 'action'])); ?>

	<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

  <?php echo $__env->make('competition_division.organizer.table',['divisions' => $competition->divisions], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
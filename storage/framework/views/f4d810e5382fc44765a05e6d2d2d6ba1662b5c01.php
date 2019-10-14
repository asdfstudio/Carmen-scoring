<?php $__env->startSection('breadcrumbs'); ?>
<?php echo Breadcrumbs::render('organizer.competition.division.choir.index',$division->competition,$division); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content-header'); ?>
	<h1>Manage Choirs</h1>

	<ul class="actions-group">
		<?php if (app('Illuminate\Contracts\Auth\Access\Gate')->check('addChoir', $division)): ?>
			<li>
				<?php echo e(link_to_route('organizer.competition.division.choir.create','Add a choir',[$division->competition,$division], ['class' => 'action'])); ?>

			</li>
		<?php endif; ?>
	</ul>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

  <?php echo $__env->make('competition_division_choir.organizer.list',['choirs' => $division->choirs], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
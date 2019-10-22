<?php $__env->startSection('breadcrumbs'); ?>
<?php echo Breadcrumbs::render('organizer.competition.division.round.index',$division->competition,$division); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content-header'); ?>
	<h1>Manage Rounds</h1>

	<ul class="actions-group">
		<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create',['App\Round',$division])): ?>
			<li>
				<?php echo e(link_to_route('organizer.competition.division.round.create','Add a round',[$division->competition,$division], ['class' => 'action'])); ?>

			</li>
		<?php endif; ?>

	</ul>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

  <?php echo $__env->make('competition_division_round.organizer.list', ['rounds' => $division->rounds], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
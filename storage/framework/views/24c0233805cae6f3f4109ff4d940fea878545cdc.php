<?php $__env->startSection('breadcrumbs'); ?>
	<?php echo Breadcrumbs::render('organizer.competition.division.show',$competition,$division); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content-header'); ?>
	<h1>Scoring Settings</h1>

	<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $division)): ?>
		<ul class="actions-group">
			<li><?php echo e(link_to_route('organizer.competition.division.edit','Edit Division',[$competition, $division],['class' => 'action'])); ?></li>
			<li><?php echo e(link_to_route('organizer.competition.division.award.settings.edit','Edit Award Settings',[$competition, $division],['class' => 'action'])); ?></li>
		</ul>
	<?php endif; ?>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

		<?php echo $__env->make('division.partial.single', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

		<h3>Award Settings</h3>
		
		<?php echo $__env->make('division_award_settings.organizer.list', ['awardSettings' => $division->awardSettings], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
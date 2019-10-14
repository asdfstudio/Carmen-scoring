<?php $__env->startSection('breadcrumbs'); ?>
	<?php echo Breadcrumbs::render('organizer.competition.division.judge.index', $division->competition,$division); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content-header'); ?>
	<h1>Manage Judges</h1>

	<ul class="actions-group">
		<?php if (app('Illuminate\Contracts\Auth\Access\Gate')->check('createJudge', $division)): ?>
			<li>
				<?php echo e(link_to_route('organizer.competition.division.judge.create','Add a judge',[$division->competition,$division], ['class' => 'action'])); ?>

			</li>
		<?php endif; ?>

		<?php if (app('Illuminate\Contracts\Auth\Access\Gate')->check('importJudges', $division)): ?>
		<li>
			<?php echo e(link_to_route('organizer.competition.division.judge.import','Import judges',[$division->competition,$division], ['class' => 'action'])); ?>

		</li>
		<?php endif; ?>
	</ul>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>



  <?php echo $__env->make('competition_division_judge.organizer.list',['judges' => $division->judges], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
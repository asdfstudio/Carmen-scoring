<?php $__env->startSection('content-header'); ?>
	<h1>Awards</h1>
	<ul class="actions-group">
		<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('createAward' , $division)): ?>
			<li>
				<?php echo e(link_to_route('organizer.competition.division.award.create','Create new award', [$division->competition->id, $division->id], ['class' => 'action'])); ?>

			</li>
		<?php endif; ?>

		<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage' , ['App\Award', $division])): ?>
		  <li>
				<?php echo e(link_to_route('organizer.competition.division.award.manage','Manage division awards', [$division->competition->id, $division->id], ['class' => 'action'])); ?>

			</li>
		<?php endif; ?>

		<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('assign' , ['App\Award', $division])): ?>
			<li>
				<?php echo e(link_to_route('organizer.competition.division.award.assign', 'Assign awards', [$division->competition->id, $division->id], ['class' => 'action'])); ?>

			</li>
		<?php endif; ?>
	</ul>

<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>

	<h2>Caption Specific Awards</h2>

	<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $division)): ?>
		<?php echo e(link_to_route('organizer.competition.division.award.settings.edit', 'Edit Award Settings', [$division->competition_id, $division], ['class' => 'action mv'])); ?>

	<?php endif; ?>

	<?php echo $__env->make('division_award_settings.organizer.list', ['awardSettings' => $division->awardSettings], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

	<h2>Other Awards</h2>
  <?php echo $__env->make('award.organizer.list', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
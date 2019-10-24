<?php $__env->startSection('content-header'); ?>
	<h1><?php echo e($soloDivision->name); ?></h1>

	<ul class="actions-group">
		<li><?php echo e(link_to_route('organizer.competition.show','Back to Competition',[$competition],['class' => 'action'])); ?></li>
    <li><?php echo e(link_to_route('organizer.competition.solo-division.edit','Edit Solo Division',[$competition, $soloDivision],['class' => 'action'])); ?></li>
    <li><?php echo e(link_to_route('organizer.competition.solo-division.manage','Manage Performers',[$competition, $soloDivision],['class' => 'action'])); ?></li>
		<li><?php echo e(link_to_route('organizer.competition.solo-division.results','View Results',[$competition, $soloDivision],['class' => 'action'])); ?></li>
	</ul>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
		<ul class="list-group">
      <li class="list-group-item">Status: <?php echo e($soloDivision->status); ?></li>
      <li class="list-group-item">Max Performers: <?php echo e($soloDivision->max_performers); ?></li>
      <li class="list-group-item">Scoring Sheet: <?php echo e($soloDivision->sheet->name); ?></li>
			<li class="list-group-item">Category #1 Name: <?php echo e($soloDivision->category_1); ?></li>
			<li class="list-group-item">Category #2 Name: <?php echo e($soloDivision->category_2); ?></li>

			<?php if($soloDivision->status_slug == 'finalized'): ?>
				<li class="list-group-item">Results URL: <?php echo e(link_to_route('results.solo-division.show', null, [$soloDivision, $soloDivision->access_code], ['target' => '_blank'])); ?></li>
			<?php endif; ?>
    </ul>

		<ul class="actions-group mv">
		<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('activateScoring', $soloDivision)): ?>
			<li><?php echo form($activateScoringForm); ?></li>
		<?php endif; ?>

		<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('completeScoring', $soloDivision)): ?>
			<li><?php echo form($completeScoringForm); ?></li>

		<?php endif; ?>

		<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('finalizeScoring', $soloDivision)): ?>
			<li><?php echo form($finalizeScoringForm); ?></li>

		<?php endif; ?>
		</ul>

    <h2>Judges</h2>

    <?php if($soloDivision->judges->count() > 0): ?>
      <ul class="list-group">
        <?php $__currentLoopData = $soloDivision->judges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $judge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <li class="list-group-item"><?php echo e($judge->full_name); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </ul>
    <?php else: ?>
      <p>There are no judges set up for this solo division.</p>
    <?php endif; ?>



    <h2>Performers</h2>

    <?php if($soloDivision->performers->count() > 0): ?>
      <?php echo $__env->make('performer.organizer.table', ['performers' => $soloDivision->performers], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php else: ?>
      <p>There are no performers set up for this solo division.</p>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
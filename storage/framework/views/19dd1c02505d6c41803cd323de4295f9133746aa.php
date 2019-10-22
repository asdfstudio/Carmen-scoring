<?php if($competition->divisions->isEmpty()): ?>
	<p>There are no divisions.</p>
<?php endif; ?>

<?php if(!$competition->divisions->isEmpty()): ?>
<table class="table table-striped table-bordered">
  <tr>
  	<th>Name</th>
		<th>Edit</th>
		<th>Set Up</th>
		<th>Status</th>
    <th>Weighting</th>
    <th>Scoring</th>
    <th>Sheet</th>
		<!--<th>Rounds</th>
    <th>Choirs</th>
    <th>Judges</th>
    <th>Penalties</th>
    <th>Awards</th>-->
  </tr>

  <?php $__currentLoopData = $competition->divisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $division): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <tr>
  	<td><?php echo e(link_to_route('organizer.competition.division.show', $division->name, [$competition, $division])); ?></td>
		<td>
			<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $division)): ?>
				<?php echo e(link_to_route('organizer.competition.division.edit', 'Edit', [$competition,$division])); ?>

			<?php endif; ?>
		</td>
		<td>
			<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $division)): ?>
				<?php echo e(link_to_route('organizer.competition.division.board', 'Set Up', [$competition,$division])); ?>

			<?php endif; ?>
		</td>
		<td><?php echo $division->status_label('small'); ?></td>
    <td><?php if($division->captionWeighting): ?><?php echo e($division->captionWeighting->name); ?> <?php endif; ?></td>
    <td><?php if($division->scoringMethod): ?><?php echo e($division->scoringMethod->name); ?> <?php endif; ?></td>
    <td><?php if($division->sheet): ?><?php echo e($division->sheet->name); ?> <?php endif; ?></td>

		<!--<td>
			<?php $anchor = $division->rounds->count() > 0 ? $division->rounds->count() : 'Set Up';?>
			<?php echo e(link_to_route('organizer.competition.division.round.index', $anchor, [$competition,$division])); ?>


		</td>
    <td>
			<?php $anchor = $division->choirs->count() > 0 ? $division->choirs->count() : 'Set Up';?>

			<?php echo e(link_to_route('organizer.competition.division.choir.index', $anchor, [$competition,$division])); ?>


		</td>
    <td>
			<?php $anchor = $division->judges->count() > 0 ? $division->judges->count() : 'Set Up';?>

			<?php echo e(link_to_route('organizer.competition.division.judge.index', $anchor, [$competition,$division])); ?>


		</td>
    <td><?php echo e($division->penalties->count()); ?></td>
    <td><?php echo e($division->awards->count()); ?></td>-->
  </tr>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</table>
<?php endif; ?>

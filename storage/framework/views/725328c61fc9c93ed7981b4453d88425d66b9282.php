<?php if($competition->schedules->isEmpty()): ?>
	<p>There are no schedules.</p>
<?php endif; ?>

<?php if(!$competition->schedules->isEmpty()): ?>
<table class="table table-striped table-bordered">
  <tr>
  	<th>Name</th>
		<th>Actions</th>
  </tr>

  <?php $__currentLoopData = $competition->schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <tr>
  	<td><?php echo e($schedule->name); ?> </td>
		<td>
			<?php echo e(link_to_route('organizer.competition.schedule.edit', 'Edit Name', [$competition,$schedule], ['class' => 'action'])); ?>


			<?php echo e(link_to_route('organizer.competition.schedule.show', 'View Schedule', [$competition,$schedule], ['class' => 'action'])); ?>


			<?php echo e(link_to_route('organizer.competition.schedule.builder', 'Schedule Builder', [$competition,$schedule], ['class' => 'action'])); ?>

		</td>
  </tr>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</table>
<?php endif; ?>

<?php if($competition->schedules->isEmpty()): ?>
	<p>There are no schedules.</p>
<?php endif; ?>

<?php if(!$competition->schedules->isEmpty()): ?>
<table class="table table-striped table-bordered">
  <tr>
  	<th>Name</th>
		<th>Actions</th>
  </tr>

  <?php foreach($competition->schedules as $schedule): ?>
  <tr>
  	<td><?php echo e($schedule->name); ?> </td>
		<td>
			<?php echo e(link_to_route('organizer.competition.schedule.edit', 'Edit Name', [$competition,$schedule], ['class' => 'action'])); ?>


			<?php echo e(link_to_route('organizer.competition.schedule.show', 'View Schedule', [$competition,$schedule], ['class' => 'action'])); ?>


			<?php echo e(link_to_route('organizer.competition.schedule.builder', 'Schedule Builder', [$competition,$schedule], ['class' => 'action'])); ?>

		</td>
  </tr>
  <?php endforeach; ?>
</table>
<?php endif; ?>

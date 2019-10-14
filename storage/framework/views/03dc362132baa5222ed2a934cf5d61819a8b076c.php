<?php if($competitions->isEmpty()): ?>
	<p>There are no competitions.</p>
<?php endif; ?>

<?php if(!$competitions->isEmpty()): ?>
<table class="table table-striped table-bordered">
  <tr>
		<th>Status</th>
  	<th>Competition Name</th>
    <th>City</th>
    <th>State</th>
    <!--<th>Divisions</th>-->
    <th>Edit</th>
		<th>Delete</th>
  </tr>

  <?php foreach($competitions as $competition): ?>
  <tr>
		<td><?php echo $competition->status_label(); ?></td>
  	<td><?php echo e(link_to_route('organizer.competition.show', $competition->name, [$competition])); ?></td>
    <td><?php if($competition->place): ?> <?php echo e($competition->place->city); ?> <?php endif; ?></td>
    <td><?php if($competition->place): ?> <?php echo e($competition->place->state); ?> <?php endif; ?></td>
    <!--<td><?php echo e(link_to_route('organizer.competition.division.index', $competition->divisions->count(), [$competition])); ?></td>-->
    <td>


    	<?php if(!$competition->is_archived): ?>
				<?php if (app('Illuminate\Contracts\Auth\Access\Gate')->check('update', $competition)): ?>
					<?php echo e(link_to_route('organizer.competition.edit', 'Edit', [$competition], ['class' => 'action'])); ?>

				<?php endif; ?>
      <?php else: ?>
				<!--Archived, no editing allowed-->
			<?php endif; ?>

			<?php if (app('Illuminate\Contracts\Auth\Access\Gate')->check('replicate', $competition)): ?>
				<?php echo e(link_to_route('organizer.competition.clone', 'Duplicate', [$competition], ['class' => 'action'])); ?>

			<?php endif; ?>
    </td>
		<td>
			<?php if (app('Illuminate\Contracts\Auth\Access\Gate')->check('destroy',$competition)): ?>
				<?php echo form($deleteCompetitionForm,['url' => route('organizer.competition.destroy',[$competition])]); ?>

			<?php endif; ?>
		</td>
  </tr>
  <?php endforeach; ?>
</table>
<?php endif; ?>

<?php if(!$divisions->isEmpty()): ?>
	<ul class="list-group">
  <?php foreach($divisions as $division): ?>
  <li class="list-group-item"><?php echo e(link_to_route('organizer.competition.division.show', $division->name, [$division->competition,$division])); ?>

		<?php echo $division->status_label('pull-right'); ?>

	</li>
  <?php endforeach; ?>
  </ul>
<?php endif; ?>

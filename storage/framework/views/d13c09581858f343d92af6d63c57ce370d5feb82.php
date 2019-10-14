<?php if(!$soloDivisions->isEmpty()): ?>
	<ul class="list-group">
  <?php foreach($soloDivisions as $soloDivision): ?>
  <li class="list-group-item"><?php echo e(link_to_route('organizer.competition.solo-division.show', $soloDivision->name, [$soloDivision->competition,$soloDivision])); ?>

		<?php echo $soloDivision->status_label('pull-right'); ?>

	</li>
  <?php endforeach; ?>
  </ul>
<?php endif; ?>

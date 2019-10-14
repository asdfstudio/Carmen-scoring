<?php if(!$divisions->isEmpty()): ?>
	<ul class="list-group">
  <?php foreach($divisions as $division): ?>
  <li class="list-group-item round">

		<?php echo e(link_to_route('judge.competition.division.details', $division->name, [$competition,$division], ['class' => 'name'])); ?>


		<?php echo $division->status_label('pull-right'); ?>


		<?php if($division->rounds): ?>
			<div class="clearfix mt">
			<?php foreach($division->rounds as $round): ?>
				<?php echo e(link_to_route('judge.round.scores.summary', $round->name, [$competition, $division, $round], ['class' => 'action status-' . $round->status_slug, 'title' => $round->status])); ?>

			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	</li>
  <?php endforeach; ?>
  </ul>
<?php endif; ?>

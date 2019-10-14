<?php if($competitions->isEmpty()): ?>
	<p>There are no competitions.</p>
<?php endif; ?>

<?php if(!$competitions->isEmpty()): ?>
<ul class="list-group">

  <?php foreach($competitions as $competition): ?>
  <li class="list-group-item">
  	<h3><?php echo e(link_to_route('judge.competition.show', $competition->name, [$competition])); ?></h3>
    <span><?php if($competition->place): ?> <?php echo e($competition->place->city); ?>, <?php echo e($competition->place->state); ?> <?php endif; ?></span>
  </li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>

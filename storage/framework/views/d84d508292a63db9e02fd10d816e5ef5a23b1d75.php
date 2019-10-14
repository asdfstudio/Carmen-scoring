<?php if($division->choirs->isEmpty()): ?>
	<p>There are no choirs. <?php echo e(link_to_route('organizer.competition.division.choir.create','Add one',[$division->competition,$division])); ?></p>
<?php endif; ?>

<?php if(!$division->choirs->isEmpty()): ?>
<table class="table table-striped table-bordered">
  <tr>
  	<th>School</th>
    <th>Choir Name</th>
    <th>City</th>
    <th>State</th>
  </tr>

  <?php foreach($division->choirs as $choir): ?>
  <tr>

    <td><?php if($choir->school): ?> <?php echo e($choir->school->name); ?> <?php endif; ?></td>
    <td><?php echo e(link_to_route('organizer.competition.division.choir.show',$choir->name,[$division->competition,$division,$choir])); ?></td>
    <td><?php if($choir->school AND $choir->school->place): ?> <?php echo e($choir->school->place->city); ?> <?php endif; ?></td>
    <td><?php if($choir->school AND $choir->school->place): ?> <?php echo e($choir->school->place->state); ?> <?php endif; ?></td>
  </tr>
  <?php endforeach; ?>
</table>
<?php endif; ?>

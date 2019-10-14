<h1>Choirs</h1>

<?php if($choirs->isEmpty()): ?>
	<p>There are no choirs.</p>
<?php endif; ?>

<?php if(!$choirs->isEmpty()): ?>
<table class="table table-striped table-bordered">
  <tr>
  	<th>Choir Name</th>
    <th>School</th>
    <th>City</th>
    <th>State</th>
    <th>Edit</th>
  </tr>

  <?php foreach($choirs as $choir): ?>
  <tr>
  	<td><?php echo e(link_to_route('admin.choir.show', $choir->name, [$choir])); ?></td>
    <td><?php if($choir->school): ?> <?php echo e(link_to_route('admin.school.show', $choir->school->name, [$choir->school])); ?> <?php endif; ?></td>
    <td><?php if($choir->school AND $choir->school->place): ?> <?php echo e($choir->school->place->city); ?> <?php endif; ?></td>
    <td><?php if($choir->school AND $choir->school->place): ?> <?php echo e($choir->school->place->state); ?> <?php endif; ?></td>
    <td><?php echo e(link_to_route('admin.choir.edit', 'Edit', [$choir])); ?></td>
  </tr>
  <?php endforeach; ?>
</table>
<?php endif; ?>

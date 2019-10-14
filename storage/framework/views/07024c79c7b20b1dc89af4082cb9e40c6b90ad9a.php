<h1>Judges</h1>

<?php if($judges->isEmpty()): ?>
	<p>There are no judges.</p>
<?php endif; ?>

<?php if(!$judges->isEmpty()): ?>
<table class="table table-striped table-bordered">
  <tr>
  	<th>First Name</th>
    <th>Last Name</th>
    <th>Email</th>
    <th>Edit</th>
  </tr>

  <?php foreach($judges as $judge): ?>
  <tr>
  	<td><?php echo e(link_to_route('admin.judge.show', $judge->first_name, [$judge])); ?></td>
    <td><?php echo e(link_to_route('admin.judge.show', $judge->last_name, [$judge])); ?></td>
    <td><?php echo e($judge->email); ?></td>
    <td><?php echo e(link_to_route('admin.judge.edit', 'Edit', [$judge])); ?></td>
  </tr>
  <?php endforeach; ?>
</table>
<?php endif; ?>

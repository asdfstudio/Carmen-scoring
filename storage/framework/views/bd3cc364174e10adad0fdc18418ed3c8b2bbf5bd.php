<?php if($schools->isEmpty()): ?>
	<p>There are no schools.</p>
<?php endif; ?>

<?php if(!$schools->isEmpty()): ?>
<ul class="list-group">
  <?php foreach($schools as $school): ?>
    <li class="school list-group-item">

      <span class="name"><?php echo e($school->name); ?></span>

      <?php if($school->place): ?>
        <span class="location"><?php echo e($school->place->city_state()); ?></span>
      <?php endif; ?>

      <ul class="actions-group">
        <li><?php echo e(link_to_route('admin.school.edit', 'Edit', [$school], ['class' => 'action'])); ?></li>
      </ul>



    </li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>

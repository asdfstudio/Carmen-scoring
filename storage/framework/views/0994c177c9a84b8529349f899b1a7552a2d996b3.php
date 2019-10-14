<?php if($choirs->isEmpty()): ?>
	<p>There are no choirs.</p>
<?php endif; ?>

<?php if(!$choirs->isEmpty()): ?>
<ul class="list-group">
  <?php foreach($choirs as $choir): ?>
    <li class="choir list-group-item">

      <?php if($choir->school): ?>
        <span class="school"><?php echo e(link_to_route('admin.school.edit', $choir->school->name, [$choir->school])); ?></span>
      <?php endif; ?>

      <span class="name"><?php echo e(link_to_route('admin.choir.show', $choir->name, [$choir])); ?></span>

      <?php if($choir->school AND $choir->school->place): ?>
        <span class="location"><?php echo e($choir->school->place->city_state()); ?></span>
      <?php endif; ?>

			<?php if($choir->directors->count() > 0): ?>
				<div class="">
					Director:
					<?php echo e($choir->directors->pluck('full_name')->implode(', ')); ?>

				</div>
			<?php endif; ?>

      <ul class="actions-group">
        <li><?php echo e(link_to_route('admin.choir.edit', 'Edit', [$choir], ['class' => 'action'])); ?></li>
				<li><?php echo e(link_to_route('admin.choir.show', 'Manage Directors', [$choir], ['class' => 'action'])); ?></li>
      </ul>



    </li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>

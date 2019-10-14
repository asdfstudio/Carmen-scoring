<li class="round card" data-resource-type="round" data-resource-id="<?php echo e($round->id); ?>">
  <div class="name"><?php echo e($round->name); ?></div>

  <div class="actions">
    <!--<a class="edit-resource" data-resource-type="round" data-resource-id="<?php echo e($round->id); ?>" href="#" data-resource="<?php echo e(json_encode($round)); ?>">Edit</a>
    <a class="remove-resource" data-resource-type="round" data-resource-id="<?php echo e($round->id); ?>" href="<?php echo e(route('organizer.competition.division.round.destroy',[$division->competition,$division,$round])); ?>">Remove </a>-->
  </div>
</li>

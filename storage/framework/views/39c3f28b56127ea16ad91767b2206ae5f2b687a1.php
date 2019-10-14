<li class="choir card list-group-item" data-resource-type="choir" data-resource-id="<?php echo e($choir->id); ?>">

  <?php if($choir->school): ?>
    <span class="school"><?php echo e($choir->school->name); ?></span>
  <?php endif; ?>

  <span class="name"><?php echo e($choir->name); ?></span>

  <div class="actions">
    <?php if (app('Illuminate\Contracts\Auth\Access\Gate')->check('removeChoir', $division)): ?>
      <a class="remove-resource" data-resource-type="choir" data-resource-id="<?php echo e($choir->id); ?>" data-csrf-token="<?php echo e(csrf_token()); ?>" href="<?php echo e(route('organizer.competition.division.choir.destroy',[$division->competition,$division,$choir])); ?>">Remove</a>
    <?php endif; ?>
  </div>
</li>

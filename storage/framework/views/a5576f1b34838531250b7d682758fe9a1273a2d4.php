<li class="judge card list-group-item" data-resource-type="judge" data-resource-id="<?php echo e($judge->id); ?>">


  <span class="name"><?php echo e($judge->full_name); ?></span>

  <ul class="captions-group">
    <?php foreach($judge->captions as $caption): ?>
      <li class="<?php echo e($caption->background_css); ?> caption label"><?php echo e($caption->name); ?></li>
    <?php endforeach; ?>
  </ul>

  <div class="actions">
    <?php if (app('Illuminate\Contracts\Auth\Access\Gate')->check('removeJudge', $division)): ?>
      <a class="remove-resource" data-resource-type="judge" data-resource-id="<?php echo e($judge->id); ?>" data-csrf-token="<?php echo e(csrf_token()); ?>" href="<?php echo e(route('organizer.competition.division.judge.destroy',[$division->competition,$division,$judge])); ?>">Remove</a>
    <?php endif; ?>
  </div>
</li>

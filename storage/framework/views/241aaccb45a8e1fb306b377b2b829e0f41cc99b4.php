
<ul class="list-group">
  <?php $__currentLoopData = $awardSettings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $awardSetting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php $captionName = $awardSetting->caption ? $awardSetting->caption->name : 'Overall'; ?>
    <li class="list-group-item"><?php echo e($captionName); ?> Award Count: <?php echo e($awardSetting->award_count); ?></li>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>

<?php if($place): ?>
  <dl>
    <dd><?php echo e($place->address); ?> <?php echo e($place->address_2); ?></dd>
    <dd><?php echo e($place->city); ?>, <?php echo e($place->state); ?> <?php echo e($place->postal_code); ?></dd>
  </dl>
<?php endif; ?>

<?php $__env->startSection('body-header'); ?>
  <div class="body-header body-width">
    <a href="/"><img src="/images/Carmen-Logo-185x54.png" alt="Carmen Scoring System"  /></a>
  </div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('body-content'); ?>
  <div class="collapse content body-width">
    <?php echo $__env->yieldContent('content'); ?>
  </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
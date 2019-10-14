<?php $__env->startSection('body-header'); ?>
  <style>@import  "/css/director-form.css";</style>
<?php $__env->stopSection(); ?>

<div class="board-list choirs" id="choir-list">
  <div class="list-header">
    <h3>Choirs</h3>
    <span class="card-count" data-resource-type="choir"><?php echo e(count($division->choirs)); ?></span>
  </div>

  <?php echo form($newChoirForm); ?>


  <?php echo $__env->make('choir.board.list', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

  <a class="add-resource" data-resource-type="choir" href="#">Add a choir</a>
</div>

<?php $__env->startSection('body-footer'); ?>
  <script src="/js/director-form.js"></script>
<?php $__env->stopSection(); ?>
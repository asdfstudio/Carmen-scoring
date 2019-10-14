<div class="board-list judges" id="judge-list">
  <div class="list-header">
    <h3>Judges</h3>
    <span class="card-count" data-resource-type="judge"><?php echo e(count($division->judges)); ?></span>
  </div>

  <?php echo form($newJudgeForm); ?>


  <?php echo $__env->make('judge.board.list', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

  <a class="add-resource" data-resource-type="judge" href="#">Add a judge</a>
</div>

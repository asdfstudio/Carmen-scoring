<div class="board-list rounds" id="round-list">
  <div class="list-header">
    <h3>Rounds</h3>
    <span class="card-count" data-resource-type="round"><?php echo e(count($division->rounds)); ?></span>
  </div>

  <?php echo form($newRoundForm); ?>


  <?php echo $__env->make('round.board.list', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

  <a class="add-resource" data-resource-type="round" href="#">Add a round...</a>
</div>

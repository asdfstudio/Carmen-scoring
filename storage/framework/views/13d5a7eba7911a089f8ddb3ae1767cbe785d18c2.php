<ul class="rounds cards" data-resource-type="round">
  <?php echo $__env->make('round.board.list-item-prototype', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

  <?php foreach($division->rounds as $round): ?>
    <?php echo $__env->make('round.board.list-item', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <?php endforeach; ?>
</ul>

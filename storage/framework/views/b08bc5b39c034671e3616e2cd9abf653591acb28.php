<ul class="judges cards list-group" data-resource-type="judge">
  <?php echo $__env->make('judge.board.list-item-prototype', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

  <?php foreach($division->judges as $judge): ?>
    <?php echo $__env->make('judge.board.list-item', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <?php endforeach; ?>
</ul>

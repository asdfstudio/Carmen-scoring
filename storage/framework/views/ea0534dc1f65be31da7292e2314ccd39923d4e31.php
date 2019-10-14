<ul class="choirs cards list-group" data-resource-type="choir">
  <?php echo $__env->make('choir.board.list-item-prototype', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

  <?php foreach($division->choirs as $choir): ?>
    <?php echo $__env->make('choir.board.list-item', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <?php endforeach; ?>
</ul>

<table class="table table-striped table-bordered">
  <tr>
    <th>Category</th>
    <th>Choir</th>
    <th>Performer</th>
    <th>Score</th>
    <th>Place</th>

    <?php if(!empty($judges)): ?>
      <?php $__currentLoopData = $judges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $judge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <th><?php echo e($judge->full_name); ?></th>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>

  </tr>

  <?php $__currentLoopData = $performers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $performer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
      <td>
        <?php echo $performer->category_label('small'); ?>

      </td>
      <td>
        <?php if($performer->choir): ?>
          <?php echo e($performer->choir->full_name); ?>

        <?php endif; ?>
      </td>
      <td>
        <?php echo e(link_to_route('organizer.competition.solo-division.performer.show', $performer->name, [$competition, $soloDivision, $performer])); ?>

      </td>
      <td><?php echo e($performer->score); ?></td>
      <td><?php echo e($performer->rank); ?></td>

      <?php if(!empty($judges)): ?>
        <?php $__currentLoopData = $judges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $judge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <td><?php echo e($performer->judgeScores[$judge->id]); ?></td>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <?php endif; ?>
    </tr>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</table>

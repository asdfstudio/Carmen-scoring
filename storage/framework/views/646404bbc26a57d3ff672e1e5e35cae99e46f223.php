<?php if($division->judges->isEmpty()): ?>
	<p>There are no judges. <?php echo e(link_to_route('organizer.competition.division.judge.create','Add one',[$division->competition,$division])); ?></p>
<?php endif; ?>

<?php if(!$division->judges->isEmpty()): ?>
<table class="table table-striped table-bordered">
  <tr>
  	<th>Judge Name</th>

    <?php $__currentLoopData = $captions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $caption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <th><?php echo e($caption->name); ?></th>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <th>Edit</th>
  </tr>

  <?php $__currentLoopData = $judges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $judge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <tr>

    <td><?php echo e(link_to_route('organizer.competition.division.judge.show',$judge->full_name, [$division->competition, $division, $judge])); ?></td>

    <?php $__currentLoopData = $captions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $caption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <td>
    	<?php if(in_array($caption->id, $judge->captions->pluck('id')->toArray() )): ?>
    		<?php echo e($caption->name); ?>

      <?php endif; ?>
    </td>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <td><?php echo e(link_to_route('organizer.competition.division.judge.edit','Edit', [$division->competition, $division, $judge])); ?></td>
  </tr>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</table>
<?php endif; ?>

<?php if(!$standing->choirs): ?>
  <p>
    No standings to display
  </p>
<?php endif; ?>

<?php if($standing->choirs): ?>
<?php echo e(Form::open(['method' => 'post'])); ?>

<ul class="list-group">
  <?php $__currentLoopData = $standing->choirs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $choir): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <li class="list-group-item standing">
      <span class="choir"><?php echo e($choir->full_name); ?></span>

      <div class="details">
        <span class="raw_rank">Original Rank: <?php echo e($choir->pivot->raw_rank); ?></span>

        <?php echo e(Form::hidden('choirs['.$choir->id.'][raw_rank]', $choir->pivot->raw_rank)); ?>



        <span class="final_rank">
          <span class="text">Final Rank:</span>
          <?php echo e(Form::number('choirs['.$choir->id.'][final_rank]', $choir->pivot->final_rank)); ?>

        </span>
      </div>




    </li>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>

<?php echo e(Form::submit('Save Modified Standings', ['class' => 'btn btn-primary'])); ?>

<?php echo e(Form::close()); ?>

<?php endif; ?>

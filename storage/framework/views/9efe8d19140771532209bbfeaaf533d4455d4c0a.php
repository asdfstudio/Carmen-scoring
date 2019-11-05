<?php if(!$standing->choirs): ?>
  <p>
    No standings to display
  </p>
<?php endif; ?>

<?php if($standing->choirs): ?>
<ul class="list-group">
  <?php $__currentLoopData = $standing->choirs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $choir): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <li class="list-group-item standing">
      <span class="choir"><?php echo e($choir->full_name); ?></span>

      <div class="details">

        <?php if($standing->is_consensus_scoring): ?>
          <span class="raw_rank">Original Rank: <?php echo e($choir->pivot->raw_rank); ?></span>
        <?php endif; ?>

        <span class="final_rank">
          <span class="text">Final Rank:</span>
          <?php echo e($choir->pivot->final_rank); ?>

          <?php if($standing->choirs->where('pivot.final_rank', $choir->pivot->final_rank)->count() > 1): ?>
            <span class="tied">tied</span>
          <?php endif; ?>
        </span>
        
      </div>

    </li>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>
<?php endif; ?>

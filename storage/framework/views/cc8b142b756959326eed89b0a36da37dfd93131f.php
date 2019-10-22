<?php $__env->startSection('body-header'); ?>
  <div class="body-header body-width">
    <a href="/"><img src="/images/Carmen-Logo-185x54.png" alt="Carmen Scoring System"  /></a>

    <?php if(isset($division)): ?>
      <div class="heading-container">
        <h1><?php echo e($division->competition->name); ?></h1>
        <h2 class="subheader"><?php echo e($division->competition->place->city_state()); ?></h2>
      </div>
    <?php endif; ?>
  </div>
<?php $__env->stopSection(); ?>



<?php $__env->startSection('body-content'); ?>
  <div class="collapse content body-width">

    <?php echo $__env->yieldContent('breadcrumbs'); ?>

    <?php if(isset($division)): ?>
      <h1 class="division-heading"><?php echo e($division->name); ?> Results</h1>

      <?php if(isset($access_code)): ?>
        <ul class="actions-group centered">
      		<li>
      			<a href="<?php echo e(route('results.division.show', [$division, $access_code])); ?>" class="<?php if($current_page == 'awards'): ?> active <?php endif; ?> action">Awards</a>
      		</li>
      		<li>
      			<a href="<?php echo e(route('results.division.standings', [$division, $access_code])); ?>" class="<?php if($current_page == 'standings'): ?> active <?php endif; ?> action">Standings</a>
      		</li>

          <?php $__currentLoopData = $division->rounds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $round): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li>
              <?php
              $active = $current_page == 'round_'.$round->id ? 'active' : false;
              ?>
        			<a href="<?php echo e(route('results.division.round.show', [$division, $round, $access_code])); ?>" class="<?php echo e($active); ?> action"><?php echo e($round->name); ?></a>
        		</li>

            <?php $__currentLoopData = $round->targets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $target): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php if($target AND $target->sources->count() > 1): ?>
                <li>
                  <?php
                  $active = $current_page == 'round_shared_'.$round->id ? 'active' : false;
                  ?>
            			<a href="<?php echo e(route('results.division.round-shared.show', [$division, $round, $target->id, $access_code])); ?>" class="<?php echo e($active); ?> action"><?php echo e($target->name); ?> > Source Rounds</a>
            		</li>
              <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      	</ul>
      <?php endif; ?>

    <?php endif; ?>

    <?php echo $__env->yieldContent('content'); ?>
  </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
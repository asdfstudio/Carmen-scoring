<?php $__env->startSection('content-header'); ?>
  <h1>Source Division/Round Scores</h1>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('division_navigation_bar'); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('round_navigation_bar'); ?>
  <?php if(isset($round) AND isset($division->rounds)): ?>
    <div class="round-navigation-bar body-width">
      <ul class="round-navigation">
        <?php $__currentLoopData = $division->rounds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php $active_class = $rd->id == $round->id ? 'active' : '';?>
          <li class="round-<?php echo e($rd->status_slug); ?>">
            <a href="<?php echo e(route('judge.round.scores.summary', [$competition, $division, $rd])); ?>" class="<?php echo e($active_class); ?>">
              <?php echo e($rd->name); ?>


              <?php echo $rd->status_label('round-navigation-link-status'); ?>

            </a>
          </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </ul>
    </div>
  <?php endif; ?>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>


    <ul class="list-group horizontal">

      <?php if($division->captionWeighting->slug == '60-40'): ?>
    		<li class="list-group-item">
    			<?php $active = $division->captionWeighting->slug == '60-40' ? 'active division-scoring-method' : false; ?>
    			<a class="score-view-toggle <?php echo e($active); ?>" href="#weighted" data-score-view="weighted">Weighted</a>

    			<?php if($active): ?>
    				<span>(<?php echo e($division->captionWeighting->full_name); ?>)</span>
    			<?php else: ?>
    				<span>(<?php echo e($division->captionWeighting->name); ?>)</span>
    			<?php endif; ?>

    		</li>
      <?php endif; ?>


      <?php $active = $division->captionWeighting->slug == '50-50' ? 'active division-scoring-method' : false; ?>
  		<li class="list-group-item">
  			<a class="score-view-toggle <?php echo e($active); ?>" href="#raw" data-score-view="raw">Raw</a>
  		</li>

      <?php if($isScoringActive): ?>
        <li class="list-group-item">
    			<a class="score-view-toggle" href="#edit" data-score-view="edit">Edit Scores</a>
    		</li>
      <?php endif; ?>
  	</ul>

  <?php echo $__env->make('scores.spreadsheet-legend', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

  <?php echo $__env->make('scores.judge.spreadsheet',[
    'choirs' => $choirs,
    'division' => $division,
    'judge' => $judge,
    //'round' => $round
  ], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('body-footer'); ?>
    <!--  Decide if we want to split the spreadsheet table  -->
    <?php //$splitTheTable = $round->choirs->count() > 1 ? 'true' : 'false'; ?>
    <?php $splitTheTable = 'true'; ?>
    <script type="text/javascript">
      splitTheTable = <?php echo e($splitTheTable); ?>

    </script>

  <script src="/js/responsive-tables.js"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
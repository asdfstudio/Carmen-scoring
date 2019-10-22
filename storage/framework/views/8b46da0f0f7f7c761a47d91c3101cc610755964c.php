<?php $__env->startSection('division_navigation_bar'); ?>
  <?php if(isset($division)): ?>
    <div class="division-navigation-bar body-width">
      <ul class="division-navigation tab-links">
        <!--<li>
          <a href="#overview">Overview</a>
        </li>-->
        <li>
          <a href="#scoring" class="active tab-link" data-tab-id="scoring">Settings</a>
        </li>
        <li>
          <a href="#choirs" class="tab-link" data-tab-id="choirs">Choirs</a>
        </li>
        <li>
          <a href="#judges" class="tab-link" data-tab-id="judges">Judges</a>
        </li>
        <li>
          <a href="#rounds" class="tab-link" data-tab-id="rounds">Rounds</a>
        </li>

        <li>
          <a href="#penalties" class="tab-link" data-tab-id="penalties">Penalties</a>
        </li>
        <li>
          <a href="#" class="tab-link" data-tab-id="awards">Awards</a>
        </li>
        <li>
          <a href="#" class="tab-link" data-tab-id="standings">Final Standings</a>
        </li>
        <li class="scoring">
          <a href="<?php echo e(route('judge.competition.division.scoring', [$competition, $division])); ?>">Enter Scoring Mode</a>
        </li>
      </ul>
    </div>
  <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>



  <div class="row">

    <div data-tab-id="scoring" class="active tab-content col-xs-12 col-sm-12">
      <h2>Scoring Settings</h2>
      <?php echo $__env->make('division.partial.single', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </div>

    <div data-tab-id="choirs" class="tab-content col-xs-12 col-sm-12">
      <h2>Choirs</h2>
      <?php echo $__env->make('competition_division_choir.judge.list',['choirs' => $division->choirs], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </div>

    <div data-tab-id="judges" class="tab-content col-xs-12 col-sm-12">
    	<h2>Judges</h2>
    	<?php echo $__env->make('competition_division_judge.judge.list',['judges' => $division->judges], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </div>

    <div data-tab-id="rounds" class="tab-content col-xs-12 col-sm-12">
      <h2>Rounds</h2>
      <?php echo $__env->make('competition_division_round.judge.list', ['rounds' => $division->rounds], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </div>

    <div data-tab-id="penalties" class="tab-content col-xs-12 col-sm-12">
      <h2>Penalties</h2>
      <?php echo $__env->make('penalty.organizer.list', ['penalties' => $division->penalties], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </div>

    <div data-tab-id="awards" class="tab-content col-xs-12 col-sm-12">
    	<h2>Awards</h2>
    	<?php echo $__env->make('award.organizer.list', ['awards' => $division->awards], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </div>

    <div data-tab-id="standings" class="tab-content col-xs-12 col-sm-12">
    	<h2>Finals Standings</h2>
    	<?php echo $__env->make('competition_division_standing.judge.show', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </div>


  </div>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
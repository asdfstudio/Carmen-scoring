<?php $__env->startSection('content-header'); ?>
  <h1>Convert People, Types, &amp; Choir Relationships</h1>
  <a href="<?php echo e(route('dedup')); ?>" class="action">Back</a>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
  
  <p>
    This script converts the type (Judge, Director, Choreographer) of every person in
    the database to the new table structure. This will allow one person to have multiple
    types.  This script also looks up existing relationships between judges and divisions
    and between directors/choreographers and choirs, and converts those associations into
    the new table structure.
  </p>

  <p>It is safe to run this script more than once. It will NOT double-convert the data.</p>

  <p>Use the "Dry Run" button to preview the changes without actually modifying the database. Use the "Convert" button to do the conversion.</p>

  <p style="margin: 20px 0;">
    <?php if(!$run): ?>
      <script>
        function disableButtons(){
          var b = document.getElementsByClassName('run-button');
          for(var i=0; i<b.length; i++){
            b[i].setAttribute('disabled', 'disabled');
          }
        }
      </script>
      <a href="<?php echo e(url()->current()); ?>?run" class="run-button btn btn-primary" onClick="disableButtons(); this.innerHTML = 'Please wait...';">Convert</a>
      <a href="<?php echo e(url()->current()); ?>?dryrun" class="run-button btn btn-default" onClick="disableButtons(); this.innerHTML = 'Please wait...';">Dry Run</a>
    <?php endif; ?>
    <?php if($run || $dryrun): ?>
      <a href="<?php echo e(url()->current()); ?>" class="run-button btn btn-default" onClick="disableButtons(); this.innerHTML = 'Please wait...';">Clear</a>
    <?php endif; ?>
  </p>

  <?php if($run_dryrun_error): ?>
    <div class="alert alert-danger">This script cannot be set to "run" and "dryrun" at the same time.</div>
  <?php endif; ?>

  <?php if(($run || $dryrun) && !$run_dryrun_error): ?>

      <hr>

      <h3>Judges</h3>

      <div style="max-height: 600px; overflow-y: scroll; padding: 20px; border: 1px #c0c0c0 solid; margin-bottom: 50px;">

          <?php if(empty($judges)): ?>
            <p>No judges were found.</p>
          <?php endif; ?>

          <?php $__currentLoopData = $judges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $judge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div style="padding: 20px;">
              <p><?php echo e($judge->intro); ?></p>
              <ul>
                <li>Assigned to <?php echo e(count($judge->divisions)); ?> divisions</li>
              </ul>
              <?php if(isset($judge->run_messages) && !empty($judge->run_messages)): ?>
                <div class="alert alert-info">
                  <?php $__currentLoopData = $judge->run_messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $message; ?><br>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
              <?php endif; ?>
            </div>

            <hr>

          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

      </div>

      <hr>

      <h3>Directors</h3>

      <div style="max-height: 600px; overflow-y: scroll; padding: 20px; border: 1px #c0c0c0 solid; margin-bottom: 50px;">

          <?php if(empty($directors)): ?>
            <p>No directors were found.</p>
          <?php endif; ?>

          <?php $__currentLoopData = $directors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $director): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div style="padding: 20px;">
              <p><?php echo e($director->intro); ?></p>
              <?php if(isset($director->choir) || isset($director->school)): ?>
                <ul>
              <?php endif; ?>
                <?php if(isset($director->choir)): ?>
                  <li><?php echo e($director->choir); ?></li>
                <?php endif; ?>
                <?php if(isset($director->school)): ?>
                  <li><?php echo e($director->school); ?></li>
                <?php endif; ?>
              <?php if(isset($director->choir) || isset($director->school)): ?>
                </ul>
              <?php endif; ?>
              <?php if(isset($director->run_messages) && !empty($director->run_messages)): ?>
                <div class="alert alert-info">
                  <?php $__currentLoopData = $director->run_messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $message; ?><br>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
              <?php endif; ?>
            </div>

            <hr>

          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

      </div>

      <hr>

      <h3>Choreographers</h3>

      <div style="max-height: 600px; overflow-y: scroll; padding: 20px; border: 1px #c0c0c0 solid; margin-bottom: 50px;">

          <?php if(empty($choreographers)): ?>
            <p>No directors were found.</p>
          <?php endif; ?>

          <?php $__currentLoopData = $choreographers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $choreographer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div style="padding: 20px;">
              <p><?php echo e($choreographer->intro); ?></p>
              <?php if(isset($choreographer->choir) || isset($choreographer->school)): ?>
                <ul>
              <?php endif; ?>
                <?php if(isset($choreographer->choir)): ?>
                  <li><?php echo e($choreographer->choir); ?></li>
                <?php endif; ?>
                <?php if(isset($choreographer->school)): ?>
                  <li><?php echo e($choreographer->school); ?></li>
                <?php endif; ?>
              <?php if(isset($choreographer->choir) || isset($choreographer->school)): ?>
                </ul>
              <?php endif; ?>
              <?php if(isset($choreographer->run_messages) && !empty($choreographer->run_messages)): ?>
                <div class="alert alert-info">
                  <?php $__currentLoopData = $choreographer->run_messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $message; ?><br>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
              <?php endif; ?>
            </div>

            <hr>

          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

      </div>

  <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
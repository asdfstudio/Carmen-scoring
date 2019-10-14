<?php $__env->startSection('content-header'); ?>
  <h1>Merge Duplicates Automatically</h1>
  <a href="<?php echo e(route('admin.dedup')); ?>" class="action">Back</a>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
  
  <p>
    This script checks for dubplicates in the 'people' table of the database and
    merges them into a single record for each person. Data such as their role as a
    choir director, choir choreographer, and judge will be preserved.
  </p>

  <p>It is safe to run this script more than once. It will NOT harm data that has already been merged.</p>

  <p>Use the "Dry Run" button to preview the changes without actually modifying the database. Use the "Merge" button to do the conversion.</p>

  <p style="margin: 20px 0;">
    <p><form><label><input type="checkbox" id="thorough" style="margin: 4px 6px 4px 2px;"> Thorough Search (Slow! Compares names and schools instead of just email addresses)</label></form></p>
    <a href="<?php echo e(url()->current()); ?>?run" class="merge run-button btn btn-primary">Merge</a>
    <a href="<?php echo e(url()->current()); ?>?dryrun" class="merge run-button btn btn-default">Dry Run</a>
    <?php if($run || $dryrun): ?>
      <a href="<?php echo e(url()->current()); ?>" class="run-button btn btn-default">Clear</a>
    <?php endif; ?>
  </p>

  <?php if($run_dryrun_error): ?>
    <div class="alert alert-danger">This script cannot be set to "run" and "dryrun" at the same time.</div>
  <?php endif; ?>

  <?php if(($run || $dryrun) && !$run_dryrun_error): ?>

      <hr>

      <h3>Duplicates (<?php echo e(count($people)); ?> records for <?php echo e(count($people_merged_info)); ?> individuals)</h3>
      
      <p><form><label><input type="checkbox" id="show-single" style="margin: 4px 6px 4px 2px;"> Include non-duplicate records</label></form></p>
      
      <style>.single{display: none;}</style>
      
      <div style="max-height: 600px; overflow-y: scroll; padding: 20px; border: 1px #c0c0c0 solid; margin-bottom: 50px;">
        
        <?php foreach($people_merged_info as $person): ?>
          <div style="padding: 20px;" class="<?php echo e($person->single_or_multiple); ?>">
            <p><?php echo e($person->full_name); ?> (<?php echo e($person->email); ?>, <?php echo e($person->tel); ?>)</p>
            <ul>
              <li><?php echo e(count($person->people_list)); ?> record(s) in the database</li>
              <li>Person IDs: <?php echo e(implode(', ', $person->people_list)); ?></li>
              <li>Person ID after merge: <?php echo e($person->id); ?></li>
              <?php if($person->user_id): ?>
                <li>User ID: <?php echo e($person->user_id); ?></li>
              <?php else: ?>
                <li>No user account associated with this person</li>
              <?php endif; ?>
              <?php if($person->divisions_judged): ?>
                <li>Judge of <?php echo e(count($person->divisions_judged)); ?> division(s) and <?php echo e(count($person->divisions_pivot_captions)); ?> caption(s) with <?php echo e(count($person->comments)); ?> comment(s)</li>
              <?php endif; ?>
              <?php if($person->choirs_directed): ?>
                <li>Director of <?php echo e(count($person->choirs_directed)); ?> choirs(s)</li>
              <?php endif; ?>
              <?php if($person->choirs_choreographed): ?>
                <li>Choreographer of <?php echo e(count($person->choirs_choreographed)); ?> choirs(s)</li>
              <?php endif; ?>
            </ul>
            <?php if(isset($person->run_messages) && !empty($person->run_messages)): ?>
              <div class="alert alert-info">
                <?php foreach($person->run_messages as $message): ?>
                  <?php echo $message; ?><br>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
            
            <hr>
            
          </div>

        <?php endforeach; ?>
        
      </div>


  <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('body-footer'); ?>
  <script>
    const urlParams = new URLSearchParams(window.location.search);
    const has_thorough = urlParams.has('thorough');
    
    if(has_thorough){
      $('#thorough').prop('checked', true);
    }
    
    $('.run-button').click(function(e){
      
      e.preventDefault();
      
      if(typeof $(this).prop('disabled') === 'undefined' || $(this).prop('disabled') === false){
        
        console.log($(this).prop('disabled'));
        
        var thorough = $('#thorough').prop('checked');
        var url = $(this).attr('href');

        if(thorough && $(this).hasClass('merge')){
          url += '&thorough';
        }
        
        this.innerHTML = 'Please wait...';

        $('.run-button').prop('disabled', true).attr('disabled', 'disabled');
        
        location = url;

      }
      
    });
    
    $('#show-single').change(function(){
      if($(this).prop('checked')){
        $('.single').show();
      } else {
        $('.single').hide();
      }
    });
  </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->startSection('content-header'); ?>
  <h1>Merge Duplicates Manually</h1>
  <a href="<?php echo e(route('admin.dedup')); ?>" class="action">Back</a>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
  
  <p>
    This script finds duplicate choirs based on name or school association. (Very similar names will be lumped together in case of typos.)
    Then you can manually merge them if you confirm that they are the same school.
  </p>
  
  <p style="margin: 20px 0;">
      <a href="<?php echo e(url()->current()); ?>?group_by=both" class="btn btn-primary">Group By Name &amp; School</a>
      <a href="<?php echo e(url()->current()); ?>?group_by=name" class="btn btn-primary">Group By Name</a>
      <a href="<?php echo e(url()->current()); ?>?group_by=school" class="btn btn-primary">Group By School</a>
      <a href="<?php echo e(url()->current()); ?>" class="btn btn-default">Clear</a>
  </p>
  
  <hr>
  
  <?php if(!empty($choirs_merged_info)): ?>
    <div class="alert alert-info">
      <p>The following records have been merged:</p>
      <ul>
        <?php foreach($choirs_merged_info as $info): ?>
          <li><?php echo e($info->name); ?> &mdash; IDs: <?php echo e(implode(', ', $info->choir_list)); ?> have been merged into <?php echo e($info->id); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <?php if(!empty($choirs_grouped)): ?>
    
    <h3><?php echo e($dup_count); ?> Potential Duplicates</h3>
    
    <p>
      On each row, check the box for the records that should be merged. Leave records unchecked if they don't
      need to be merged with the others on that row.  When you are done making selections, click the "Merge
      Selected" button at the bottom of the page.
    </p>
    
    <form method="post">
      <div style="max-height: 600px; overflow-y: scroll; border: 1px #c0c0c0 solid; margin-bottom: 50px;">

        <?php foreach($choirs_grouped as $key => $group): ?>
          <?php if(count($group) > 1): ?>
            <div style="display: flex;">
              <?php foreach($group as $choir): ?>
                <div style="flex-grow: 1; margin: 20px; padding: 20px; background: #f7f7f7; border: 1px #c0c0c0 solid; border-radius: 8px;">
                  <label style="white-space: pre"><input type="checkbox" name="duplicates[<?php echo e($key); ?>][]" value="<?php echo e($choir->id); ?>">  <?php echo e($choir->name); ?> (ID: <?php echo e($choir->id); ?>)</label>
                  <ul>
                    <li>School: <?php echo e(isset($choir->school) ? $choir->school->name . ' (' . $choir->school->id . ')' : 'None'); ?></li>
                    <li>Directors: 
                      <?php foreach($choir->directors as $i => $director): ?>
                        <?php if($i < count($choir->directors)-1): ?>
                          <?php echo e($director->first_name); ?> <?php echo e($director->last_name); ?> (<?php echo e($director->id); ?>), 
                        <?php else: ?>
                          <?php echo e($director->first_name); ?> <?php echo e($director->last_name); ?> (<?php echo e($director->id); ?>)
                        <?php endif; ?>
                      <?php endforeach; ?>
                    </li>
                  </ul>
                </div>
              <?php endforeach; ?>
            </div>

            <hr>

          <?php endif; ?>
        <?php endforeach; ?>

      </div>
      <?php echo e(csrf_field()); ?>

      <button name="merge" class="run-button btn btn-primary">Merge Selected</button>
    </form>
  <?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
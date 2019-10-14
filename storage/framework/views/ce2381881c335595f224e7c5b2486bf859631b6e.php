<?php $__env->startSection('content-header'); ?>
  <h1>Merge Duplicates Manually</h1>
  <a href="<?php echo e(route('admin.dedup')); ?>" class="action">Back</a>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
  
  <p>
    This script finds duplicate schools based on names or locations (or very similar names/locations that may be differentiated by a typo)
    and allows you to manually merge them if you confirm that they are the same school.
  </p>
  
  <p style="margin: 20px 0;">
      <a href="<?php echo e(url()->current()); ?>?group_by=both" class="btn btn-primary">Group By Name &amp; Location</a>
      <a href="<?php echo e(url()->current()); ?>?group_by=name" class="btn btn-primary">Group By Name</a>
      <a href="<?php echo e(url()->current()); ?>?group_by=location" class="btn btn-primary">Group By Location</a>
      <a href="<?php echo e(url()->current()); ?>" class="btn btn-default">Clear</a>
  </p>
  
  <hr>
  
  <?php if(!empty($schools_merged_info)): ?>
    <div class="alert alert-info">
      <p>The following records have been merged:</p>
      <ul>
        <?php foreach($schools_merged_info as $info): ?>
          <li><?php echo e($info->name); ?> &mdash; IDs: <?php echo e(implode(', ', $info->school_list)); ?> have been merged into <?php echo e($info->id); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <?php if(!empty($schools_grouped)): ?>
    
    <h3><?php echo e($dup_count); ?> Potential Duplicates</h3>
    
    <p>
      On each row, check the box for the records that should be merged. Leave records unchecked if they don't
      need to be merged with the others on that row.  When you are done making selections, click the "Merge
      Selected" button at the bottom of the page.
    </p>
    
    <form method="post">
      <div style="max-height: 600px; overflow-y: scroll; border: 1px #c0c0c0 solid; margin-bottom: 50px;">

        <?php foreach($schools_grouped as $key => $group): ?>
          <?php if(count($group) > 1): ?>
            <div style="display: flex;">
              <?php foreach($group as $school): ?>
                <div style="flex-grow: 1; margin: 20px; padding: 20px; background: #f7f7f7; border: 1px #c0c0c0 solid; border-radius: 8px;">
                  <label style="white-space: pre"><input type="checkbox" name="duplicates[<?php echo e($key); ?>][]" value="<?php echo e($school->id); ?>">  <?php echo e($school->name); ?> (ID: <?php echo e($school->id); ?>)</label>
                  <ul>
                    <li>Choirs: 
                      <?php foreach($school->choirs as $i => $choir): ?>
                        <?php if($i < count($school->choirs)-1): ?>
                          <?php echo e($choir->id); ?>, 
                        <?php else: ?>
                          <?php echo e($choir->id); ?>

                        <?php endif; ?>
                      <?php endforeach; ?>
                    </li>
                    <li>
                      Address:<br>
                      <address style="padding: 5px 0 0 10px; font-style: italic;">
                        <?php if(!empty($school->place->address)): ?>
                          <?php echo e($school->place->address); ?><br>
                        <?php endif; ?>
                        <?php if(!empty($school->place->address_2)): ?>
                          <?php echo e($school->place->address2); ?><br>
                        <?php endif; ?>
                        <?php if(!empty($school->place->city_state())): ?>
                          <?php echo e($school->place->city_state()); ?>

                        <?php endif; ?>
                        <?php if(!empty($school->place->postal_code)): ?>
                          <?php echo e($school->place->postal_code); ?>

                        <?php endif; ?>
                      </address>
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
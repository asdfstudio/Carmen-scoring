<?php $__env->startSection('content-header'); ?>
  <h1>Merge Duplicates Manually</h1>
  <a href="<?php echo e(route('admin.dedup')); ?>" class="action">Back</a>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
  
  <p>
    This script finds duplicate names (or very similar names that may just be differentiated by a typo)
    and allows you to manually merge them if you confirm that they are the same person.
  </p>
  
  <p>
    On each row, check the box for the records that should be merged. Leave records unchecked if they don't
    need to be merged with the others on that row.  When you are done making selections, click the "Merge
    Selected" button at the bottom of the page.
  </p>
  
  <hr>
  
  <?php if(!empty($people_merged_info)): ?>
    <div class="alert alert-info">
      <p>The following records have been merged:</p>
      <ul>
        <?php foreach($people_merged_info as $info): ?>
          <li><?php echo e($info->full_name); ?> &mdash; IDs: <?php echo e(implode(', ', $info->people_list)); ?> have been merged into <?php echo e($info->id); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <h3><?php echo e($potential_dup_count); ?> Potential Duplicates</h3>
  
  <form method="post">
    <div style="max-height: 600px; overflow-y: scroll; border: 1px #c0c0c0 solid; margin-bottom: 50px;">

      <?php foreach($people_grouped as $key => $group): ?>
        <?php if(count($group) > 1): ?>
          <div style="display: flex;">
            <?php foreach($group as $person): ?>
              <div style="flex-grow: 1; margin: 20px; padding: 20px; background: #f7f7f7; border: 1px #c0c0c0 solid; border-radius: 8px;">
                <label style="white-space: pre"><input type="checkbox" name="duplicates[<?php echo e($key); ?>][]" value="<?php echo e($person->id); ?>">  <?php echo e($person->first_name); ?> <?php echo e($person->last_name); ?> (ID: <?php echo e($person->id); ?>)</label>
                <ul>
                  <li><?php echo e($person->email); ?></li>
                  <li><?php echo e($person->tel); ?></li>
                  <li>Types: <?php echo e(implode(', ', $person->typeNames())); ?></li>
                  <li>Choirs: <?php echo e(implode(', ', $person->choirIds())); ?></li>
                  <li>Schools: <?php echo e(implode(', ', $person->schoolIds())); ?></li>
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

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
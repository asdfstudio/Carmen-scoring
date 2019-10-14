<?php $__env->startSection('content-header'); ?>
  <h1>List Duplicate People</h1>
  <a href="<?php echo e(route('admin.dedup')); ?>" class="action">Back</a>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>

    <p>
      This page shows duplicate records, using either the primary email or the name of the person to
      find duplicates. If you are grouping duplicates by name, the script will group names that are
      very similar, with only 1 to 3 characters different. This helps spot records that are duplicated
      because of a typo, but it may also result in false positives that can be ignored.
    </p>

    <p style="margin: 20px 0;">
        <a href="<?php echo e(url()->current()); ?>?group_by=email" class="btn btn-primary">Group By Email</a>
        <a href="<?php echo e(url()->current()); ?>?group_by=name" class="btn btn-primary">Group By Name</a>
        <a href="<?php echo e(url()->current()); ?>" class="btn btn-default">Clear</a>
    </p>
    
    <?php if($group_by && !$has_duplicates): ?>
      <hr>
      <p>There are <?php echo e(count($people_grouped)); ?> people in the database with no duplicates based on <?php echo e($group_by); ?>.</p>
    <?php endif; ?>

    <?php if($group_by && $has_duplicates): ?>
      <hr>
      <p><strong>There are <?php echo e($dup_count); ?> people with potential duplicates based on <?php echo e($group_by); ?>.</strong></p>
      <hr>
      <ul class="list-group">
        <?php foreach($people_grouped as $group): ?>
          <?php if(count($group) > 1): ?>
            <li class="list-group-item">
              <?php echo e($group[0]->first_name); ?> <?php echo e($group[0]->last_name); ?> (<?php echo e($group[0]->email); ?>) appears <?php echo e(count($group)); ?> times:
              <table style="width: 100%; margin-top: 10px;">
                <thead>
                  <tr>
                    <th style="width: 40%; padding: 2px 4px; border: 1px #c0c0c0 solid;">Person Entries</th>
                    <th style="width: 60%; padding: 2px 4px; border: 1px #c0c0c0 solid;">Associated Info</th></tr>
                </thead>
                <tbody>
                  <?php foreach($group as $person): ?>
                    <tr>
                      <td style="width: 40%; padding: 2px 4px; border: 1px #c0c0c0 solid;">
                        <?php echo e($person->first_name); ?> <?php echo e($person->last_name); ?><br>
                        Person ID: <?php echo e($person->id); ?>

                      </td>
                      <td style="width: 60%; padding: 2px 4px; border: 1px #c0c0c0 solid;">
                        <ul>
                          <li>
                            Email(s):
                            <?php if($person->emails_additional): ?>
                              <?php echo e($person->email); ?>, <?php echo e($person->emails_additional); ?>

                            <?php else: ?>
                              <?php echo e($person->email); ?>

                            <?php endif; ?>
                          </li>
                          <li>Phone: <?php echo e($person->tel); ?></li>
                          <li>Type(s): <?php echo e(str_replace('App\\', '', implode(', ', $person->typeNames()))); ?></li>
                          <li>Choir(s): <?php echo e(implode(', ', $person->choirIds())); ?></li>
                          <li>Schools(s): <?php echo e(implode(', ', $person->schoolIds())); ?></li>
                        <?php if(isset($person->user)): ?>
                          <li>User Account:
                            <ul>
                              <li>User ID: <?php echo e($person->user->id); ?></li>
                              <li>Username: <?php echo e($person->user->username); ?></li>
                              <li>User Email: <?php echo e($person->user->email); ?></li>
                            </ul>
                          </li>
                        <?php endif; ?>
                        </ul>
                      </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </li>
          <?php endif; ?>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
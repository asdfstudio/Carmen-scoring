<?php $__env->startSection('content-header'); ?>
  <h1>List Duplicate Schools</h1>
  <a href="<?php echo e(route('admin.dedup')); ?>" class="action">Back</a>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>

    <p>
      This page shows duplicate school records, based on name and location.
    </p>

    <?php if(!$has_duplicates): ?>
      <hr>
      <p>There are <?php echo e(count($schools_grouped)); ?> schools in the database with no duplicates.</p>
    <?php endif; ?>

    <?php if($has_duplicates): ?>
      <hr>
      <p><strong>There are <?php echo e($dup_count); ?> schools with potential duplicates.</strong></p>
      <hr>
      <ul class="list-group">
        <?php foreach($schools_grouped as $group): ?>
          <?php if(count($group) > 1): ?>
            <li class="list-group-item">
              <?php echo e($group[0]->name); ?> appears <?php echo e(count($group)); ?> times:
              <ul>
                <?php foreach($group as $school): ?>
                  <li>
                    ID: <?php echo e($school->id); ?><br>
                    Name: <?php echo e($school->name); ?><br>
                    Choirs:
                      <?php foreach($school->choirs as $i => $choir): ?>
                        <?php if($i < count($school->choirs)-1): ?>
                          <?php echo e($choir->id); ?>, 
                        <?php else: ?>
                          <?php echo e($choir->id); ?>

                        <?php endif; ?>
                      <?php endforeach; ?>
                    <br>
                    Address:<br>
                    <address style="padding: 5px 0 0 10px; font-style: italic;">
                      <?php if(!empty($school->place->address)): ?>
                        <?php echo e($school->place->address); ?><br>
                      <?php endif; ?>
                      <?php if(!empty($school->place->address2)): ?>
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
                <?php endforeach; ?>
              </ul>
            </li>
          <?php endif; ?>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
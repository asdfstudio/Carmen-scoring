<?php if($organizations->isEmpty()): ?>
  <p>There are no organizations.</p>
<?php endif; ?>

<?php if(!$organizations->isEmpty()): ?>
<ul class="list-group">
  <?php $__currentLoopData = $organizations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $organization): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <li class="list-group-item">
      <?php echo e(link_to_route('admin.organization.show', $organization->name, [$organization])); ?>

    </li>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>
<?php endif; ?>
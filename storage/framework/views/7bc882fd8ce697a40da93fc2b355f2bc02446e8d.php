<?php $number_selector_class = isset($class) ? $class : false; ?>
<?php $criterion_id = $criterion ? $criterion->id : false; ?>

<ul class="number-selector <?php echo e($number_selector_class); ?>">
  <?php for($i = $start; $i <= $end; $i = $i + $interval): ?>

    <?php
    $class = $score * 10 == $i * 10 ? 'current' : '';
    $number_no_decimal = $i * 10;
    ?>
    <li class="number">
      <a href="#<?php echo e($i); ?>" class="<?php echo e($class); ?>" data-criterion-id="<?php echo e($criterion_id); ?>" data-number="<?php echo e($number_no_decimal); ?>"><?php echo e($i); ?></a>
    </li>
  <?php endfor; ?>
</ul>

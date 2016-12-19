<?php $number_selector_class = isset($class) ? $class : false; ?>
<?php $criterion_id = $criterion ? $criterion->id : false; ?>

<ul class="number-selector {{ $number_selector_class }}">
  @for ($i = $start; $i <= $end; $i = $i + $interval)

    <?php
    $class = $score * 10 == $i * 10 ? 'current' : '';
    ?>
    <li class="number">
      <a href="#{{ $i }}" class="{{ $class }}" data-criterion-id="{{ $criterion_id }}" data-number="{{ $i }}">{{ $i }}</a>
    </li>
  @endfor
</ul>

@php $number_selector_class = isset($class) ? $class : false; @endphp
@php $criterion_id = $criterion ? $criterion->id : false; @endphp

<ul class="number-selector {{ $number_selector_class }}">
  @for ($i = $start; $i <= $end; $i = $i + $interval)

    @php
    $class = $score * 10 == $i * 10 ? 'current' : '';
    $number_no_decimal = $i * 10;
    @endphp
    <li class="number">
      <a href="#{{ $i }}" class="{{ $class }}" data-criterion-id="{{ $criterion_id }}" data-number="{{ $number_no_decimal }}">{{ $i }}</a>
    </li>
  @endfor
</ul>

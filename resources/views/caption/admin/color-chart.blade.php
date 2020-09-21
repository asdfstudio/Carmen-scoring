<!-- <h3>Color Options Key</h3> -->
<label>Choose color</label>

<ul class="list-group color-list dg-mt-0">
  @php $i = 1; @endphp
  @while ($i <= 12)
    <li class="list-group-item {{ isset($color_id) && $i == $color_id ? 'dg-active' : ''}}" data-color="{{ $i }}">
      <span class="color-name text-color-{{ $i }}">Color {{ $i }}</span>
      <ul class="swatches-list-group">
        <li class="swatch small lighter-background-color-{{ $i }}"></li>
        <li class="swatch background-color-{{ $i }}"></li>
        <li class="swatch small darker-background-color-{{ $i }}"></li>
      </ul>
    </li>
    @php $i++; @endphp
  @endwhile

</ul>

<h3>Color Options Key</h3>

<ul class="list-group color-list">
  @php $i = 1; @endphp
  @while ($i <= 12)
    <li class="list-group-item">
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

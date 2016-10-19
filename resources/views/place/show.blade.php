@if($place)
  <dl>
    <dd>{{ $place->address }} {{ $place->address_2 }}</dd>
    <dd>{{ $place->city }}, {{ $place->state }} {{ $place->postal_code }}</dd>
  </dl>
@endif

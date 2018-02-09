@foreach($standings as $standing)

  @include('standing.public_standing_table', ['standing' => $standing, 'showSponsor' => true])

@endforeach

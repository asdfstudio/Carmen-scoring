<h1>{{ $competition->name }}</h1>
    
<h3>Organizer: {{ $competition->organization->name }}</h3>

@if($competition->place)
  <h4>Location Details</h4>
  @include('place.show',['place' => $competition->place])
@endif
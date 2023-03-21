@if($choirs)

  <h3>Ensembles</h3>

  @foreach($choirs as $choir)
    <li>
      {{ link_to_route('organizer.competition.division.round.choir.show', $choir->name, [$division->competition_id,$division,$round,$choir])}}
    </li>
  @endforeach

@endif

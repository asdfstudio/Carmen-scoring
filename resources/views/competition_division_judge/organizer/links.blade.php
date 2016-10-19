@if($judges)

  <h3>Judges</h3>

  @foreach($judges as $judge)
    <li>
      {{ link_to_route('organizer.competition.division.judge.show', $judge->full_name, [$division->competition_id,$division,$judge])}}
    </li>
  @endforeach

@endif

@if($judges)

  <h3>Judges</h3>

  @foreach($judges as $judge)
    <li>
      {{ link_to_route('organizer.competition.division.round.judge.show', $judge->full_name, [$division->competition_id,$division,$round,$judge])}}
    </li>
  @endforeach

@endif

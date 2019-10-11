@if($rounds)

  <h3>Rounds</h3>

  <ul class="list-group">
    @foreach($rounds as $ro)
        @php $active_class = $round->id == $ro->id ? 'active' : '';@endphp
        {{ link_to_route('organizer.competition.division.round.show', $ro->name, [$ro->division->competition_id,$ro->division,$ro],['class' => 'list-group-item '.$active_class])}}
    @endforeach
  </ul>

@endif

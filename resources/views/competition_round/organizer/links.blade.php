@if($rounds)

  <h3>Types</h3>

  <ul class="list-group">
    @foreach($rounds as $ro)
        @php $active_class = $round->id == $ro->id ? 'active' : '';@endphp
        {{ link_to_route('organizer.competition.round.show', $ro->name, [$ro->competition->competition_id,$ro],['class' => 'list-group-item '.$active_class])}}
    @endforeach
  </ul>

@endif

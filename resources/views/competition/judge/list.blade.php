@if($competitions->isEmpty())
	<p>There are no competitions.</p>
@endif

@if(!$competitions->isEmpty())
<ul class="list-group">

  @foreach($competitions as $competition)
  <li class="list-group-item">
  	<h3>{{ link_to_route('judge.competition.show', $competition->name, [$competition]) }}</h3>
    <span>@if($competition->place) {{ $competition->place->city }}, {{ $competition->place->state }} @endif</span>
  </li>
  @endforeach
</ul>
@endif

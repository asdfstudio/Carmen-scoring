@if($penalties->isEmpty())
	<p>There are no penalties.</p>
@endif

@if(!$penalties->isEmpty())
{!! Form::open(array('route' => array('organizer.competition.division.round.choir.penalty.update_assign',$division->competition,$division, $round, $choir), 'method' => 'post')) !!}
<ul class="list-group">
  @foreach($penalties as $penalty)
	  <li class="list-group-item">
			<?php $selected = $selected_penalties->where('id', $penalty->id)->count();?>
			{{ Form::checkbox("penalties[$penalty->id]", $penalty->id, $selected) }}
			<h4>{{ $penalty->name }}</h4> - {{ $penalty->description }} <br/>
			{{ $penalty->amount }} points - {{ $penalty->apply_per_judge() }}
		</li>
  @endforeach
</ul>

{{ Form::submit('Save Penalties', ['class' => 'btn btn-primary btn-lg']) }}

{!! Form::close() !!}
@endif

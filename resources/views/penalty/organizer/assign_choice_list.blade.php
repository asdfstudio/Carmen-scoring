@if($penalties->isEmpty())
	<p>There are no penalties.</p>
@endif

@if(!$penalties->isEmpty())
{!! Form::open(array('route' => array('organizer.competition.division.round.choir.penalty.update_assign',$division->competition_id,$division,$round,$choir), 'method' => 'post')) !!}
<ul class="list-group">
  @foreach($penalties as $penalty)
		<li class="penalty list-group-item">
			@php $selected = $selected_penalties->where('id', $penalty->id)->count();@endphp
			{{ Form::checkbox("penalties[$penalty->id]", $penalty->id, $selected, ['class' => 'penalties pull-left']) }}

			<div class="group pull-left">
				<span class="name">{{ $penalty->name }}</span>
				<span class="description">{{ $penalty->description }}</span>
			</div>

			<span class="details">
				<span class="amount">{{ $penalty->amount }}</span>
				point penalty
				<span class="apply_per_judge">{{ $penalty->apply_per_judge_text() }}</span>
			</span>
		</li>
  @endforeach
</ul>

{{ Form::submit('Save Penalties', ['class' => 'btn btn-primary btn-lg']) }}

{!! Form::close() !!}
@endif

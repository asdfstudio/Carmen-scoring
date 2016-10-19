@if($awards->isEmpty())
	<p>There are no awards.</p>
@endif

@if(!$awards->isEmpty())

<ul class="list-group">
  @foreach($awards as $award)
	  <li class="list-group-item">
			<h4>{{ $award->name }}</h4>
			{{ $award->description }}
			{{ Form::label('Recipient') }}
			{{ Form::text("awards[".$award->id."][recipient]", $award->pivot->recipient) }}

			<?php
			if($award->choir)
			{
				$selected = $award->choir->id;
			} else {
				$selected = false;
			}
			?>
			{{ Form::label('Choir') }}
			{{ Form::select("awards[".$award->id."][choir_id]", $division->choirs->lists('name','id'), $selected, ['placeholder' => 'Select Choir']) }}
		</li>
  @endforeach
</ul>


@endif

@if($awards->isEmpty())
	<p>There are no awards.</p>
@endif

@if(!$awards->isEmpty())

<ul class="list-group">
  @foreach($awards as $award)
	  <li class="award list-group-item">
			<span class="name">{{ $award->name }}</span>
			<span class="description">{{ $award->description }}</span>

			<div class="form-group">
				{{ Form::label('Recipient') }}
				{{ Form::text("awards[".$award->id."][recipient]", $award->pivot->recipient, ['class' => 'form-control']) }}
			</div>


			<div class="form-group">
				@php
				if($award->choir)
				{
					$selected = $award->choir->id;
				} else {
					$selected = false;
				}
				@endphp
				{{ Form::label('Choir') }}
				{{ Form::select("awards[".$award->id."][choir_id]", $division->choirs->pluck('name','id'), $selected, ['placeholder' => 'Select Choir', 'class' => 'form-control']) }}
			</div>

		</li>
  @endforeach
</ul>


@endif

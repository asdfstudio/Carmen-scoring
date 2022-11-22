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

            @if(Request::segment(4) == 'round' || Request::segment(4) == 'division')
			<div class="form-group">
				@php
				if($award->choir)
				{
					$selected = $award->pivot->choir_id;
				} else {
					$selected = false;
				}
				@endphp
				{{ Form::label('Choir') }}
                @if (isset($round))
                {{ Form::select("awards[".$award->id."][choir_id]", $round->choirs->pluck('FullName', 'id'), $selected, ['placeholder' => 'Select Choir', 'class' => 'form-control']) }}
                @elseif (isset($division))
				{{ Form::select("awards[".$award->id."][choir_id]", $division->choirs->pluck('FullName', 'id'), $selected, ['placeholder' => 'Select Choir', 'class' => 'form-control']) }}
                @endif
			</div>
            @endif

		</li>
  @endforeach
</ul>


@endif

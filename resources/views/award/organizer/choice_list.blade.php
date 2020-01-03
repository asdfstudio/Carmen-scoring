@if($awards->isEmpty())
	<p>There are no awards.</p>
@endif

@if(!$awards->isEmpty())




<ul class="list-group">
  @foreach($awards as $award)
	  <li class="list-group-item">
			@php $selected = $selected_awards->where('id', $award->id)->count();@endphp
			{{ Form::checkbox("awards[$award->id]", $award->id, $selected, ['class' => 'awards']) }}
			<h4>{{ $award->name }}</h4>
			{{ $award->description }}

			@php
			$selected = $selected_awards->where('id', $award->id)->first();
			$sponsor = $selected ? $selected->pivot->sponsor : false;
			@endphp
			<div class="sponsor-container mv">
				{{ Form::label('Award Sponsor:') }}
				{{ Form::text("sponsors[$award->id]", $sponsor) }}
			</div>

		</li>
  @endforeach
</ul>


@endif

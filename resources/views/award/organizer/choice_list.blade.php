@if($awards->isEmpty())
	<p>There are no awards.</p>
@endif

@if(!$awards->isEmpty())


	

<ul class="list-group">
  @foreach($awards as $award)
	  <li class="list-group-item">
			<?php $selected = $selected_awards->where('id', $award->id)->count();?>
			{{ Form::checkbox("awards[$award->id]", $award->id, $selected, ['class' => 'awards']) }}
			<h4>{{ $award->name }}</h4>
			{{ $award->description }}
		</li>
  @endforeach
</ul>


@endif

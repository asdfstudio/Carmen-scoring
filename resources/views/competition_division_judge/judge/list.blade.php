@if($judges->isEmpty())
	<p>There are no judges.</p>
@endif

@if(!$judges->isEmpty())
<ul class="list-group">
  @foreach($judges as $judge)
	  <li class="judge list-group-item">
			<span class="name">{{  $judge->full_name }}</span>

      <ul class="captions-group">
      @foreach($captions as $caption)

          @if(in_array($caption->id, $judge->captions->pluck('id')->toArray() ))
            <li class="{{ $caption->slug }} caption label {{ $caption->background_css }}">{{ $caption->name }}</li>
          @endif


      @endforeach
      </ul>

			<!--<ul class="actions-group">
				<li>
					{{ link_to_route('organizer.competition.division.judge.edit', 'Edit', [$division->competition,$division,$judge], ['class' => 'action']) }}
				</li>
			</ul>-->

		</li>
  @endforeach
</ul>
@endif

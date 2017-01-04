@if($awards->isEmpty())
	<p>There are no awards.</p>
@endif

@if(!$awards->isEmpty())
<ul class="list-group">
  @foreach($awards as $award)
	  <li class="award list-group-item">
			<span class="name">{{ $award->name }}</span>

			@if($award->description)
				<span class="description">{{ $award->description }}</span>
			@endif

			<span class="owner">{{ $award->owner() }}</span>

			@if($award->pivot)
				@if($award->pivot->recipient OR $award->choir)
					<span class="recipient">
						<span class="heading">Recipient:</span>
						@if($award->pivot->recipient)
							<span class="name">{{ $award->pivot->recipient }}</span>
						@endif
						@if($award->choir)
							<span class="choir">{{ $award->choir->full_name }}</span>
						@endif
					</span>
				@endif
			@endif

			@if($award->pivot->sponsor)
				<span class="sponsor">
					<span class="heading">Sponsored by:</span>
					<span class="name">{{ $award->pivot->sponsor }}</span>
				</span>
			@endif

		</li>
  @endforeach
</ul>
@endif

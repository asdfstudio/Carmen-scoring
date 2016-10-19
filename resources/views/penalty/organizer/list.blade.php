@if($penalties->isEmpty())
	<p>There are no penalties.</p>
@endif

@if(!$penalties->isEmpty())
<ul class="list-group">
  @foreach($penalties as $penalty)
	  <li class="penalty list-group-item">
			<div class="group pull-left">

				@can('update',$penalty)
					<a class="name" href="{{ route('organizer.penalty.edit',[$penalty]) }}">{{ $penalty->name }}</a>
				@endcan

				@cannot('update',$penalty)
					<span class="name">{{ $penalty->name }}</span>
				@endcan

				<span class="description">{{ $penalty->description }}</span>



			</div>

			<span class="details pull-right">
				<span class="amount">-{{ $penalty->amount }}</span>
				points
				<span class="apply_per_judge">{{ $penalty->apply_per_judge_text() }}</span>
			</span>

			<ul class="actions-group">
				@can('update',$penalty)
					<li>
						<a class="action secondary" href="{{ route('organizer.penalty.edit',[$penalty]) }}">Edit</a>
					</li>
				@endcan
			</ul>


		</li>
  @endforeach
</ul>
@endif

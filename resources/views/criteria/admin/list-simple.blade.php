@if($criteria->isEmpty())
	<p>There are no criteria.</p>
@endif

@if(!$criteria->isEmpty())
<ul class="list-group">
  @foreach($criteria as $criterion)
    <li class="school list-group-item">

      <div class="name">
				{{ $criterion->name }}
				<div class="pull-right label">Max score: {{ $criterion->max_score }}</div>
			</div>
			<div class="description mv">{{ $criterion->description }}</div>

    </li>
  @endforeach
</ul>
@endif

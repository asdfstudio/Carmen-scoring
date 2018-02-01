@if(!$criteria->isEmpty())
<ul class="list-group">

  @foreach($criteria as $criterion)
    <li class="school list-group-item choice">

      <div class="input-container">
        {{ Form::text('criteria['.$criterion->id.'][sequence]', $criterion->pivot->sequence) }}
      </div>

      <div class="">
        <div class="name">
  				{{ $criterion->name }}
  				<div class="pull-right label">Max score: {{ $criterion->max_score }}</div>
  			</div>
  			<span class="description">{{ $criterion->description }}</span>
      </div>


    </li>
  @endforeach
</ul>
@endif

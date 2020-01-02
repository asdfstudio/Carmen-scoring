@if(!$criteria->isEmpty())
<ul class="list-group">
  @foreach($criteria as $criterion)
    <li class="school list-group-item choice">

      <div class="checkbox">
        @php $selected = $selectedCriteria->where('id', $criterion->id)->count(); @endphp
        {{ Form::checkbox('criteria['.$criterion->id.']', $criterion->id, $selected) }}
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

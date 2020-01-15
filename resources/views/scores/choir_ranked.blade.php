@if(!$division->sheet->criteria->isEmpty())
<div class="table-wrapper-responsive">
<table class="table table-striped table-bordered">
  <tr>
  	<th>Criteria</th>
    
    @foreach($division->judges as $judge)
    <th data-judge-id="{{ $judge->id }}">{{ $judge->full_name }}</th>
    @endforeach
    
    <th>Total</th>
  </tr>
  
  @foreach($division->sheet->criteria as $criterion)
  <tr data-criterion-id="{{ $criterion->id }}">
  	<td data-criterion-id="{{ $criterion->id }}">{{ $criterion->caption->name }} - {{ $criterion->name }}</td>
    
    @foreach($division->judges as $judge)
   	<td data-judge-id="{{ $judge->id }}" data-criterion-id="{{ $criterion->id }}">
    	
      @if($round->is_completed ?? $judge->id == Auth::user()->person_id)
				@php $rankedScore = $rankedScores->where('criterion_id', $criterion->id)->where('judge_id',$judge->id)->where('choir_id',$choir->id)->pluck('rank');@endphp
        @php $score = $rankedScore->first(); @endphp
        {{ $score }}
      @else
      	-
       @endif
    </td>
    @endforeach
    
    <td>
    	@if($round->is_completed)
    	@php $aggregateScore = $rankedScores->where('criterion_id',$criterion->id)->where('choir_id',$choir->id)->sum('rank');@endphp
      {{ $aggregateScore }}
      @else
      	-
      @endif
    </td>
    
  </tr>
  @endforeach
  
  <tr>
  	<td>Total</td>
    
    @foreach($division->judges as $judge)
    <th>
    	@if($round->is_completed ?? $judge->id == Auth::user()->person_id)
				@php $aggregateScore = $rankedScores->where('judge_id',$judge->id)->where('choir_id',$choir->id)->sum('rank');@endphp
        {{ $aggregateScore }}
      @else
      	-
       @endif
    </th>
    @endforeach
    
  </tr>
</table>
</div>
@endif
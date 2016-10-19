@if(!$division->sheet->criteria->isEmpty())
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
    	@if($round->is_completed OR $judge->id == Auth::user()->person_id)
    	<?php $rawScore = $rawScores->where('criterion_id', $criterion->id)->where('judge_id',$judge->id)->pluck('score');?>
      <?php $score = $rawScore->first(); ?>
      {{ $score }}
      @else
      	-
      @endif
    </td>
    @endforeach
    
    <td>
    	@if($round->is_completed)
    	<?php $aggregateScore = $rawScores->where('criterion_id',$criterion->id)->where('choir_id',$choir->id)->sum('score');?>
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
    	@if($round->is_completed OR $judge->id == Auth::user()->person_id)
    	<?php $aggregateScore = $rawScores->where('judge_id',$judge->id)->where('choir_id',$choir->id)->sum('score');?>
      {{ $aggregateScore }}
      
      @else
      	-
      @endif
    </th>
    @endforeach
    
    <th>
    	@if($round->is_completed)
    	<?php $aggregateScore = $rawScores->where('choir_id',$choir->id)->sum('score');?>
      {{ $aggregateScore }}
      @else
      	-
      @endif
    </th>
    
  </tr>
</table>
@endif


@if(!$choirs->isEmpty())
<div class="table-wrapper-responsive">
<table class="table table-striped table-bordered">
  <tr>
  	<th>Choir</th>
    
    @if(!$judges->isEmpty())
    	@foreach($judges as $judge)
      	<th>{{ link_to_route('round.scores.judge.show',$judge->full_name,[$round->division->competition,$round->division,$round,$judge]) }}</th>
      @endforeach
      
      <th>Total</th>
    @endif
  </tr>
  
  @foreach($choirs as $choir)
  <tr>
  	<td>{{ link_to_route('round.scores.choir.show',$choir->name,[$round->division->competition,$round->division,$round,$choir]) }}</td>
    
    @if(!$judges->isEmpty())
    	@foreach($judges as $judge)
      <td data-choir-id="{{ $choir->id }}" data-judge-id="{{ $judge->id }}">
      
      @if($round->is_completed ?? $judge->id == Auth::user()->person_id)
				@php 
        $rawScore = $rawScores->where('judge_id', $judge->id)->where('choir_id',$choir->id)->sum('score');
        $rawScoreLink = NULL;
        
        if($rawScore) :
          $rawScoreLink = link_to_route('round.scores.choir.judge.show',$rawScore,[$round->division->competition,$round->division,$round,$choir,$judge]);
        endif;
        @endphp
        @if($rawScoreLink) {{ $rawScoreLink }} @endif
      @else
      	-
      @endif
    </td>
      @endforeach
    @endif
    
    <td>
    	@if($round->is_completed)
				@php $aggregateScore = $rawScores->where('choir_id',$choir->id)->sum('score');@endphp
        {{ $aggregateScore }}
      @else
      	-
      @endif
    </td>
  </tr>
  @endforeach
</table>
</div>
@endif
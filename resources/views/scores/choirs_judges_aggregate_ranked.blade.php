

@if(!$choirs->isEmpty())
<div class="table-wrapper-responsive">
<table class="table table-striped table-bordered">
  <tr>
  	<th>Choir</th>
    
    @if(!$judges->isEmpty())
    	@foreach($judges as $judge)
      	<th>{{ link_to_route('competition.division.round.judge.show',$judge->full_name,[$round->division->competition,$round->division,$round,$judge]) }}</th>
      @endforeach
      
      <th>Total</th>
    @endif
  </tr>
  
  @foreach($choirs as $choir)
  <tr>
  	<td>{{ link_to_route('round.scores.choir.show.ranked',$choir->name,[$round->division->competition,$round->division,$round,$choir]) }}</td>
    
    @if(!$judges->isEmpty())
    	@foreach($judges as $judge)
        <td data-choir-id="{{ $choir->id }}" data-judge-id="{{ $judge->id }}">
        
        @if($round->is_completed ?? $judge->id == Auth::user()->person_id)
        
					@php 
          $rankedScore = $rankedScores->where('judge_id', $judge->id)->where('choir_id',$choir->id)->sum('rank');
          $rankedScoreLink = NULL;
          
          //dd($rankedScore);
          
          if($rankedScore) :
            $rankedScoreLink = link_to_route('round.scores.choir.judge.show.ranked',$rankedScore,[$round->division->competition,$round->division,$round,$choir,$judge]);
          endif;
          @endphp
          @if($rankedScoreLink) {{ $rankedScoreLink }} @endif
        
          @else
          -
        @endif
      </td>
      @endforeach
      
      <td>
        @if($round->is_completed)
					@php $aggregateScore = $rankedScores->where('choir_id',$choir->id)->sum('rank');@endphp
          {{ $aggregateScore }}
        @else
        	-
        @endif
    	</td>
    @endif
  </tr>
  @endforeach
</table>
</div>
@endif
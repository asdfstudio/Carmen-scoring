@if(!$division->sheet->criteria->isEmpty())
<div class="table-wrapper-responsive">
<table class="table table-striped table-bordered">
  <tr>
  	<th>Criteria</th>
    
    @foreach($division->choirs as $choir)
    <th data-choir-id="{{ $choir->id }}">{{ link_to_route('competition.division.round.choir.show',$choir->name, [$division->competition, $division, $round, $choir]) }}</th>
    @endforeach
  </tr>
  
  <tr>
  	<th>Total Rank Points</th>
    
    @foreach($division->choirs as $choir)
    <th data-choir-id="{{ $choir->id }}">
    	@if($round->is_completed ?? $judge->id == Auth::user()->person_id)
				@php $score = $rankedScores->where('choir_id',$choir->id)->pluck('rank')->sum();@endphp
        {{ $score }}
      @else
      	-
      @endif
    </th>
    @endforeach
  </tr>
  
  @foreach($judge->captions as $caption)
    @foreach($caption->criteria as $criterion)
    <tr data-criterion-id="{{ $criterion->id }}">
      <td data-criterion-id="{{ $criterion->id }}">{{ $criterion->caption->name }} - {{ $criterion->name }}</td>
      
      @foreach($division->choirs as $choir)
      <td data-choir-id="{{ $choir->id }}" data-criterion-id="{{ $criterion->id }}">
        @if($round->is_completed ?? $judge->id == Auth::user()->person_id)
          @php $rankedScore = $rankedScores->where('criterion_id', $criterion->id)->where('choir_id',$choir->id)->pluck('rank');@endphp
          @php $score = $rankedScore->first(); @endphp
          {{ $score }}
        @else
         -
        @endif
        
      </td>
      @endforeach
      
    </tr>
  	@endforeach
  @endforeach
  
  
  <tr>
  	<th>Total Rank Points</th>
    
    @foreach($division->choirs as $choir)
    <th data-choir-id="{{ $choir->id }}">
    	@if($round->is_completed ?? $judge->id == Auth::user()->person_id)
				@php $score = $rankedScores->where('choir_id',$choir->id)->pluck('rank')->sum();@endphp
        {{ $score }}
      @else
       -
      @endif
    </th>
    @endforeach
  </tr>
  
</table>
</div>
@endif
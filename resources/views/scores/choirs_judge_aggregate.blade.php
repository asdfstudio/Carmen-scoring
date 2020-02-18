<?php $judge_id = $judge ? $judge->id : null; ?>
@if(!$choirs->isEmpty())
<div class="table-wrapper-responsive">
<table class="table scoreboard last-col-right">
  <tr>
  	<th>Choir</th>
    <th>My Raw Score</th>

    @if($division->captionWeighting->slug == '60-40')
      <th>
        My Weighted Score
      </th>
    @endif
    @if($round->is_scoring_active == true && $judge_id == Auth::user()->person_id && $competition->organization->is_premium == 1)

    <th>Record</th>

    @endif
  </tr>

  <div id=record-app>
  @foreach($choirs as $choir)
  <tr>

  	<td>
      @if( $choir->school && $choir->school->name )
        <span class="subheading">{{ $choir->school->name }}</span>
      @endif
      {{ $choir->name }}
    </td>

    <td>
			@php $aggregateScore = $rawScores->where('choir_id',$choir->id)->where('judge_id', $judge->id)->sum('score');@endphp
      <span class="score raw">{{ $aggregateScore }}</span>
    </td>

    @if($division->captionWeighting->slug == '60-40')
      <td>
        @php $aggregateScore = $weightedScores->where('choir_id',$choir->id)->where('judge_id', $judge->id)->sum('weightedScore');@endphp
        <span class="score weighted">{{ $aggregateScore }}</span>
      </td>
    @endif

    <td>

      @if($round->is_scoring_active == true && $judge_id == Auth::user()->person_id && $competition->organization->is_premium == 1)
      <div id="controls">
        <button id="{{'recordButton-'.$choir->id }}" data-count="{{(count($choir->recordings) > 0)?$choir->recordings->first()->total:'0'}}" data-recording="0" class="rbutton" data-choir="{{$choir->id}}" data-round="{{$round->id}}" data-division="{{$round->division_id}}">Start Recording({{(count($choir->recordings) > 0)?$choir->recordings->first()->total:'0'}})</button>
        <div class="slider" id="{{'sliderId-'.$choir->id }}" style="display:none">
              <div class="line"></div>
              <div class="subline inc"></div>
              <div class="subline dec"></div>
      </div>
      </div>


      @endif
      @if($round->is_scoring_active == false AND $judge_id == Auth::user()->person_id)

        {{ link_to_route('judge.competition.division.round.choir.show', 'View My Scores', [$round->division->competition,$round->division,$round,$choir],
        ['class' => 'action'])}}

      @endif

    </td>

  </tr>
  @endforeach
      </div>
</table>
</div>
@endif

@section('body-footer')
<script>
  $(document).ready(function(){
    $('.rbutton').click(function(e){
      var choir = $(e.target).data('choir');
      var round = $(e.target).data('round');
      var division = $(e.target).data('division');
      startRecording(choir, round, division);
    });
  });
</script>
@endsection

@section('style')
<style lang="scss">
button,
.rbutton {
  background: #7f4091;
  color: #fff;
  padding: 10px 15px;
  margin: 0 5px;
  text-align: center;
  border: none;
  border-radius: 5px;
}
.cancel {
    background-color: #cccccc;
    color: #666666;
    padding: 9px 14px;
  }
</style>
@endsection

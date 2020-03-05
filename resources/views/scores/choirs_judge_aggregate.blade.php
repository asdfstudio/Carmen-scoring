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
      @php $recording_count = (count($choir->recordings) > 0)? $choir->recordings->first()->total : 0; @endphp
      <div class="audio-recorder" id="audio-recorder-{{ $choir->id }}" data-count="{{ $recording_count }}" data-choir="{{ $choir->id }}" data-round="{{ $round->id }}" data-division="{{ $round->division_id }}">
        <div class="ar-control">
          <button>
            <span class="ar-control-symbol"></span>
          </button>
        </div>
        <div class="ar-info">
          <div class="ar-title">Audio Recorder</div>
          <div class="ar-existing">{{ $recording_count }} {{ $recording_count == 1 ? 'Recording' : 'Recordings' }} on File</div>
        </div>
        <div class="ar-progress">
          <div class="ar-meter-box">
            <div class="ar-meter-bar"></div>
          </div>
          <div class="ar-progress-text">--:--</div>
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
</script>
@endsection

@section('style')
<style lang="scss">
.audio-recorder {
  position: relative;
  min-width: 240px;
  height: 75px;
  line-height: 1;
  color: #ffffff;
  background: #4b5256;
}

.ar-info {
  position: absolute;
  top: 0;
  right: 0;
  left: 75px;
  padding: 8px 4px 0 12px;
  text-align: left;
}

.ar-title {
  font-weight: bold;
  font-size: 16px;
  margin-bottom: 8px;
}

.ar-existing {
  font-size: 14px;
  color: #e5e5e5;
  margin-bottom: 5px;
  /*text-decoration: underline;
  cursor: pointer;*/
}

.ar-control {
  position: absolute;
  top: 0;
  left: 0;
  width: 75px;
  height: 75px;
  border-right: 1px #808080 solid;
}

.ar-control button {
  position: absolute;
  top: 0;
  right: 0;
  margin: 0;
  width: 100%;
  height: 100%;
  border-radius: 0;
  background: #903d9a;
  border: none;
  transition: all 0.25s ease;
}

.ar-control button:hover {
  background: #7F4091;
}

.ar-control button::after {
  content: 'RECORD';
  display: block;
  position: absolute;
  bottom: 9px;
  left: 0;
  right: 0;
  font-size: 11px;
  text-transform: uppercase;
}

.recording .ar-control button {
  background: #ee191c;
}

.recording .ar-control button:hover {
  background: #de2131;
}

.recording .ar-control button::after {
  content: 'STOP';
}

.disabled .ar-control button {
  background: transparent;
  cursor: not-allowed;
}

.disabled .ar-control button::after {
  opacity: .5;
}

.ar-control-symbol {
  display: block;
  position: absolute;
  top: 34px;
  left: 50%;
  margin: -10px;
  width: 20px;
  height: 20px;
  border-radius: 10px;
  background: #ffffff;
  transition: all 0.5s ease;
}

.recording .ar-control-symbol {
  border-radius: 0;
}

.disabled .ar-control-symbol {
  opacity: .5;
}

.ar-progress {
  position: absolute;
  bottom: 0;
  left: 75px;
  right: 0;
  height: 16px;
  background: #272B2D;
  box-sizing: content-box;
  border-top: 1px #808080 solid;
}

.ar-progress .ar-meter-box {
  position: absolute;
  top: 5px;
  right: 44px;
  bottom: 5px;
  left: 5px;
  border: 1px #606060 solid;
  border-radius: 3px;
}

.uploading .ar-progress .ar-meter-box {
  right: 74px;
}

.ar-progress .ar-meter-box .ar-meter-bar {
  position: absolute;
  top: 0;
  left: 0;
  width: 0;
  height: 100%;
  background: #ee191c;
  border-radius: 3px;
  transition: all 0.5s ease;
}

.ar-progress .ar-meter-box .ar-meter-bar::after {
  content: ' ';
  display: block;
  position: absolute;
  top: 3px;
  right: 0;
  width: 0;
  height: 0;
  border-radius: 5px;
  background: #ee191c;
  border: 0 rgba(255, 127, 127, 0) solid;
}

.recording .ar-progress .ar-meter-box .ar-meter-bar {
  width: 85%;
}

.recording .ar-progress .ar-meter-box .ar-meter-bar::after {
  top: -3px;
  right: -5px;
  width: 10px;
  height: 10px;
  border: 1px rgba(255, 127, 127, 1) solid;
  animation: glow 1s alternate ease infinite;
}

@keyframes glow {
  from {
    box-shadow: 0 0 10px 0 rgba(255, 0, 0, 0);
  }
  to {
    box-shadow: 0 0 10px 2px rgba(255, 0, 0, 1);
  }
}

.uploading .ar-progress .ar-meter-box .ar-meter-bar {
  background: #0985eb;
}

.uploading.unknown .ar-progress .ar-meter-box .ar-meter-bar {
  width: 100%;
  background: #0985eb;
  background-image: linear-gradient(
    -45deg,
    #0985eb 25%,
    #80c6ff 25%,
    #80c6ff 50%,
    #0985eb 50%,
    #0985eb 75%,
    #80c6ff 75%,
    #80c6ff
  );
  background-size: 50px 50px;
  animation: move 2s linear infinite;
}

@keyframes move {
  from {
    background-position: 0 0;
  }
  to {
    background-position: 50px 50px;
  }
}

.ar-progress .ar-progress-text {
  position: absolute;
  top: 2px;
  right: 2px;
  width: 38px;
  font-size: 11px;
  text-align: center;
}

.uploading .ar-progress .ar-progress-text {
  width: 68px;
}


/*
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
*/
</style>
@endsection

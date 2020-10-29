
@if($judgeList)

  {!! Form::open(array('route' => array('organizer.competition.division.round.choir.recordings',$competition->id,$division,$round,$choir), 'method' => 'post')) !!}
    <div class="group">
      {{ Form::hidden('division_id', $division->id) }}
      {{ Form::hidden('choir_id', $choir->id) }}
      {{ Form::hidden('round_id', $round->id) }}

      @php
        if($judge_id) {
          $selected = $judge_id;
        } else {
          $selected = false;
        }
      @endphp

      {{ Form::select('judge_id', $judgeList, $selected, ['placeholder' => 'Select a judge', 'class' => 'selectize']) }}
      {{ Form::submit('Submit', ['class' => 'btn btn-primary btn-md comment-submit-btn']) }}
    </div>
  {!! Form::close() !!}

  @if($judge_id != 'null' && $selected)
      {!! Form::open(array('route' => array('judge.recording.save'), 'class' => 'dropzone', 'id' => 'myAwesomeDropzone')) !!}
      {{ Form::hidden('division_id', $division->id) }}
      {{ Form::hidden('choir_id', $choir->id) }}
      {{ Form::hidden('round_id', $round->id) }}
      {{ Form::hidden('judge_id', $judge_id) }}
      {!! Form::close() !!}

    <div class="recording-wrapper">
      <h4>Recorded Audio Comments by {{ $judgeList[$judge_id] }}</h4>
      @php
        $recordings = $choir->recordings->where('judge_id', $judge_id)->where('round_id', $round->id);
        $recording_count = $recordings->count();
        $mode = 'player';
        $role = 'organizer'
      @endphp
    	@include('recordings.audio_recorder', ['choir' => $choir, 'round' => $round, 'recordings' => $recordings, 'recording_count' => $recording_count, 'mode' => $mode, 'role' => $role])
    </div>
  @endif
@endif

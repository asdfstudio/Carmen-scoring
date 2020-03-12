
@if($judgeList)

  {!! Form::open(array('route' => array('organizer.competition.division.round.choir.recordings',$division->competition_id,$division,$round,$choir), 'method' => 'post')) !!}
    <div class="group">
      {{ Form::hidden('division_id', $division_id) }}
      {{ Form::hidden('choir_id', $choir_id) }}
      {{ Form::hidden('round_id', $round_id) }}

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
      {{ Form::hidden('division_id', $division_id) }}
      {{ Form::hidden('choir_id', $choir_id) }}
      {{ Form::hidden('round_id', $round_id) }}
      {{ Form::hidden('judge_id', $judge_id) }}
      {!! Form::close() !!}

    <div class="recording-wrapper">
      <h4>Recorded Audio Comments by {{ $judgeList[$judge_id] }}</h4>
      @php
        $recordings = $choir->recordings->where('judge_id', $judge_id)->where('round_id', $round_id);
        $recording_count = $recordings->count();
        $mode = 'player';
        $role = 'organizer'
      @endphp
      <div class="audio-recorder tall-playlist" id="audio-recorder-{{ $choir->id }}" data-mode="{{ $mode }}" data-role="{{ $role }}" data-count="{{ $recording_count }}" data-choir="{{ $choir->id }}" data-round="{{ $round->id }}" data-division="{{ $round->division_id }}">
        <div class="ar-control">
          <button>
            <span class="ar-control-symbol"></span>
          </button>
        </div>
        <div class="ar-info">
          <div class="ar-title">Audio Player</div>
          <div class="ar-title-playback">Playing: <span></span></div>
          <div class="ar-existing">{{ $recording_count }} {{ $recording_count == 1 ? 'Recording' : 'Recordings' }} on File</div>
        </div>
        <div class="ar-playback-close"></div>
        <div class="ar-progress">
          <div class="ar-meter-box">
            <div class="ar-meter-bar"></div>
          </div>
          <div class="ar-progress-text">--:--</div>
        </div>
        <div class="ar-playlist">
          @if($recording_count === 0)
            <ol class="empty">
              <li>No recordings on file.</li>
            </ol>
          @else
            <ol>
              @foreach($recordings as $recording)
                <li id="recording-{{ $recording->id }}" data-id="{{ $recording->id }}" data-url="{{ $recording->url }}">
                  <audio><source src="{{ $recording->url }}"></audio>
                  <span class="recording-name">{{ date('M. j, Y \a\t h:m:i A (\U\T\C)', strtotime($recording->created_at)) }}</span>
                  <div class="ar-playlist-functions">
                    <button class="ar-playlist-play-pause" title="Play/Pause"></button>
                    <a class="ar-playlist-download" title="Download Recording" href="{{ $recording->url }}" target="_blank" download="{{ date('M. j, Y \a\t h:m:i A (\U\T\C)', strtotime($recording->created_at)) }}" type="application/octet-stream"></a>
                    @if($role === 'organizer' || $role === 'admin')
                      <button class="ar-playlist-delete" title="Delete Recording"></button>
                    @endif
                  </div>
                </li>
              @endforeach
            </ol>
          @endif
        </div>
      </div>
    </div>
  @endif
@endif

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
        @php
          $recordings = $choir->recordings;
          $recording_count = $recordings->count();
          $mode = 'recorder';
          $role = 'judge';
        @endphp

        <div class="audio-recorder" id="audio-recorder-{{ $choir->id }}" data-mode="{{ $mode }}" data-role="{{ $role }}" data-count="{{ $recording_count }}" data-choir="{{ $choir->id }}" data-round="{{ $round->id }}" data-division="{{ $round->division_id }}">
          <div class="ar-control">
            <button>
              <span class="ar-control-symbol"></span>
            </button>
          </div>
          <div class="ar-info">
            <div class="ar-title">Audio Recorder</div>
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
                @foreach($choir->recordings as $recording)
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

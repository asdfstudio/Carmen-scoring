<div class="audio-recorder {{ $mode === 'player' ? 'tall-playlist' : '' }}" id="audio-recorder-{{ $choir->id }}" data-mode="{{ $mode }}" data-role="{{ $role }}" data-count="{{ $recording_count }}" data-choir="{{ $choir->id }}" data-round="{{ $round->id }}" data-division="{{ $round->division_id }}">
  <div class="ar-control">
    <button>
      <span class="ar-control-symbol"></span>
    </button>
  </div>
  <div class="ar-info">
    <div class="ar-title">Audio {{ ucfirst($mode) }}</div>
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
            <div class="ar-playlist-item-name">{{ $recording->getNiceDate() }}</div>
            <div class="ar-playlist-functions">
              <button class="ar-playlist-play-pause" title="Play/Pause"></button>
              <a class="ar-playlist-download" title="Download Recording" href="{{ $recording->url }}" target="_blank" download="{{ $recording->getNiceDate() }}" type="application/octet-stream"></a>
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

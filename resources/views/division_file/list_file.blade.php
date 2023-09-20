<style>
    .ar-file-delete::after {
        content: url(data:image/svg+xml;utf8,<svg aria-hidden= "true" focusable= "false" data-prefix= "fas" data-icon= "trash" class= "svg-inline--fa fa-trash fa-w-14" role= "img" xmlns= "http://www.w3.org/2000/svg" viewBox= "0 0 448 512" ><path fill= "%23ffffff" d= "M432 32H312l-9.4-18.7A24 24 0 0 0 281.1 0H166.8a23.72 23.72 0 0 0-21.4 13.3L136 32H16A16 16 0 0 0 0 48v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16V48a16 16 0 0 0-16-16zM53.2 467a48 48 0 0 0 47.9 45h245.8a48 48 0 0 0 47.9-45L416 128H32z" ></path></svg>);
        position: absolute;
        top: 8px;
        left: 9px;
        width: 12px;
    }
</style>
<div class="audio-recorder division-file-list playlist-expanded {{ $mode === 'player' ? 'tall-playlist' : '' }}"
     id="audio-recorder-{{ $choir->id }}" data-mode="{{ $mode }}" data-role="{{ $role }}"
     data-count="{{ $recording_count }}" data-choir="{{ $choir->id }}" data-round="{{ $round->id }}"
     data-division="{{ $round->division_id }}">
    <div class="ar-control">
        <button>
            <span class="ar-control-symbol"></span>
        </button>
    </div>
    <div class="ar-info">
        {{--    <div class="ar-title">Audio {{ ucfirst($mode) }}</div>--}}
        {{--    <div class="ar-title-playback">Playing: <span></span></div>--}}
        <div class="ar-existing">{{ $recording_count }} {{ $recording_count == 1 ? 'File' : 'Files' }} </div>
    </div>
    <div class="ar-playback-close"></div>
    <div class="ar-progress">
        <div class="ar-meter-box">
            <div class="ar-meter-bar"></div>
        </div>
        {{--    <div class="ar-progress-text">--:--</div>--}}
    </div>
    <div class="ar-playlist">
        @if($recording_count === 0)
            <ol class="empty">
                <li>No files.</li>
            </ol>
        @else
            <ol>
                @foreach($recordings as $recording)
                    <li id="division-file-{{ $recording->id }}" data-id="{{ $recording->id }}"
                        data-url="{{ $recording->url }}">
                        <audio>
                            <source src="{{ $recording->url }}">
                        </audio>
                        <div class="ar-playlist-item-name">{{$recording->name}} - {{ $recording->getNiceDate() }}</div>
                        <div class="ar-playlist-functions">
                            {{--              <button class="ar-playlist-play-pause" title="Play/Pause"></button>--}}
                            <a class="ar-playlist-download" title="Download Recording" href="{{ $recording->url }}"
                               target="_blank" download="{{ $recording->getNiceDate() }}"
                               type="application/octet-stream"></a>
                            @if(isset($canDelete) && $canDelete)
                                <button class="ar-file-delete" title="Delete Recording"></button>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ol>
        @endif
    </div>
</div>
<script>
</script>

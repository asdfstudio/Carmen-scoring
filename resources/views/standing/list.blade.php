@if(!$standing->choirs)
  <p>
    No standings to display
  </p>
@endif


@if($standing->choirs)
<ul class="list-group">
  @foreach($standing->choirs as $choir)
    <li class="list-group-item standing">
      <span class="choir">{{ $choir->full_name }}</span>

      <div class="details">

        @if($standing->is_consensus_scoring)
          <span class="raw_rank">Original Rank: {{ $choir->pivot->raw_rank }}</span>
        @endif

        <span class="final_rank">
          <span class="text">Final Rank:</span>
          {{ $choir->pivot->final_rank }}
        </span>
        
      </div>

    </li>
  @endforeach
</ul>
@endif

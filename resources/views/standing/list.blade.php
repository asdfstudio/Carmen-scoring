@if(!$standing->choirs)
  <p>
    No standings to display
  </p>
@endif

@if($standing->choirs)
<ul class="list-group">
  @foreach($standing->choirs as $choir)
    @php $rating = $division->getRatings()->where('choir.id', $choir->id)->pluck('rating.name')->first(); @endphp
    <li class="list-group-item standing">
      <span class="choir">{{ $choir->full_name }}</span> @if($rating)<span class="rating">Rating: {{ $rating }}</span>@endif

      <div class="details">

        @if($standing->is_consensus_scoring)
          <span class="raw_rank">Original Rank: {{ $choir->pivot->raw_rank }}</span>
        @endif

        <span class="final_rank">
          <span class="text">Final Rank:</span>
          {{ $choir->pivot->final_rank }}
          @if($standing->choirs->where('pivot.final_rank', $choir->pivot->final_rank)->count() > 1)
            <span class="tied">tied</span>
          @endif
        </span>

      </div>

    </li>
  @endforeach
</ul>
@endif

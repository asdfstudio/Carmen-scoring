@if(!$standing->choirs)
  <p>
    No standings to display
  </p>
@endif

@if($standing->choirs)
{{ Form::open(['method' => 'post']) }}
<ul class="list-group">
  @foreach($standing->choirs as $choir)
    <li class="list-group-item standing">
      <span class="choir">{{ $choir->full_name }}</span>

      <div class="details">
        <span class="raw_rank">Original Rank: {{ $choir->pivot->raw_rank }}</span>

        {{ Form::hidden('choirs['.$choir->id.'][raw_rank]', $choir->pivot->raw_rank) }}


        <span class="final_rank">
          <span class="text">Final Rank:</span>
          {{ Form::number('choirs['.$choir->id.'][final_rank]', $choir->pivot->final_rank) }}
        </span>
      </div>




    </li>
  @endforeach
</ul>

{{ Form::submit('Save Modified Standings', ['class' => 'btn btn-primary']) }}
{{ Form::close() }}
@endif

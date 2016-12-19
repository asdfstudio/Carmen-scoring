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

        <span class="final_rank ceremony rank-{{ $choir->pivot->final_rank }}">

          <?php
          $rank_name = false;
          $final_rank = $choir->pivot->final_rank;

          if($final_rank == 1)
          {
            $rank_name = 'Champion';
          }
          else
          {
            $runner_up_number = $final_rank - 1;
            $rank_name = ordinal($runner_up_number) . ' Runner Up';
          }

          ?>
          {{ $rank_name }}
        </span>

      </div>

    </li>
  @endforeach
</ul>
@endif

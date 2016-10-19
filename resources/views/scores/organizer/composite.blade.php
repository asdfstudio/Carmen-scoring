<table class="table table-striped table-bordered">
  @foreach($captions as $caption)
    <tr class="caption-header caption-{{ $caption->slug() }}">
      <th colspan="30">
        {{ $caption->name }}
      </th>
    </tr>

    <tr>
      <th></th>

      @foreach($division->judges as $judge)
        <th>
          {{ $judge->full_name }}
        </th>
      @endforeach

      <th>Total</th>
      <th>Place</th>
    </tr>

    @foreach($division->choirs as $choir)
      <tr>
        <th>
          {{ $choir->full_name }}
        </th>
        @foreach($division->judges as $judge)
          <td>
            <?php $rank = $rankedScores->rank($judge->id, $caption->id)->where('choir_id', $choir->id)->pluck('rank')->first();?>
            {{ $rank }}
          </td>
        @endforeach

        <td>
          <?php $rank = $rankedScores->total($choir->id, $caption->id);?>
          {{ $rank }}
        </td>
        <td>
          <?php $rank = $rankedScores->total_rank($caption->id)->where('choir_id' , $choir->id)->pluck('rank')->first();?>
          {{ $rank }}
        </td>
      </tr>
    @endforeach
  @endforeach


  <tr class="caption-header caption-place">
    <th colspan="30">
      Place
    </th>
  </tr>

  <tr>
    <th></th>

    @foreach($division->judges as $judge)
      <th>
        {{ $judge->full_name }}
      </th>
    @endforeach

    <th>Total</th>
    <th>Place</th>
  </tr>

  @foreach($division->choirs as $choir)
    <tr>
      <th>
        {{ $choir->full_name }}
      </th>
      @foreach($division->judges as $judge)
        <td>
          <?php $rank = $rankedScores->rank($judge->id)->where('choir_id', $choir->id)->pluck('rank')->first();?>
          {{ $rank }}
        </td>
      @endforeach

      <td>
        <?php $rank = $rankedScores->total($choir->id);?>
        {{ $rank }}
      </td>
      <td>
        <?php $rank = $rankedScores->total_rank()->where('choir_id' , $choir->id)->pluck('rank')->first();?>
        {{ $rank }}
      </td>
    </tr>
  @endforeach

</table>

@if(!$division->sheet->criteria->isEmpty())
<div class="table-wrapper-responsive">
<table class="table table-striped table-bordered">
  <tr>
  	<th>Criteria</th>

    @foreach($division->choirs as $choir)
    <th data-choir-id="{{ $choir->id }}">
      {{ $choir->school->name }} <br />
      {{ link_to_route('organizer.round.scores.choir.judge.show',$choir->name, [$division->competition, $division, $round, $choir,$judge]) }}</th>
    @endforeach
  </tr>

  @foreach($judge->captions as $caption)

    <tr class="caption-header {{ $caption->background_css }}">
      <th colspan="3">
        {{ $caption->name }}
      </th>
      <th colspan="30"></th>
    </tr>

    @foreach($division->sheet->criteria->where('caption_id', $caption->id) as $criterion)
    <tr data-criterion-id="{{ $criterion->id }}">
      <td data-criterion-id="{{ $criterion->id }}">{{ $criterion->name }}</td>

      @foreach($division->choirs as $choir)
      <td data-choir-id="{{ $choir->id }}" data-criterion-id="{{ $criterion->id }}">
          @php $rawScore = $rawScores->where('criterion_id', $criterion->id)->where('choir_id',$choir->id)->pluck('score');@endphp
          @php $score = $rawScore->first(); @endphp
          {{ $score }}
      </td>
      @endforeach

    </tr>
    @endforeach

    <tr class="caption-raw-score {{ $caption->lighter_background_css }}">
      <th>
        Total Raw {{ $caption->name }} Score
      </th>
      @foreach($division->choirs as $choir)
        <th>
          @php $rawTotal = $rawScores->where('criterion_caption_id', $caption->id)->where('choir_id',$choir->id)->sum('score');@endphp
          {{ $rawTotal }}
        </th>
      @endforeach
    </tr>

    @if($caption->id == 1)
      <tr class="caption-weighted-score {{ $caption->background_css }}">
        <th>
          Total Weighted {{ $caption->name }} Score
        </th>
        @foreach($division->choirs as $choir)
          <th>
            @php $weightedTotal = $weightedScores->where('criterion_caption_id', $caption->id)->where('choir_id',$choir->id)->sum('weightedScore');@endphp
            {{ $weightedTotal }}
          </th>
        @endforeach
      </tr>
    @endif

    <tr class="caption-rank {{ $caption->darker_background_css }}">
      <th>
        {{ $caption->name }} Ranking
      </th>
      @foreach($division->choirs as $choir)
        <th>
          @php $rank = $rankedScores->rank($judge->id, $caption->id)->where('choir_id', $choir->id)->pluck('rank')->first();@endphp
          {{ $rank }}
        </th>
      @endforeach
    </tr>
  @endforeach

  <tr class="total-score">
  	<th>Total Score</th>

    @foreach($division->choirs as $choir)
    	<th>
      @php $weightedTotal = $rawScores->where('choir_id',$choir->id)->sum('weightedScore');@endphp
      {{ $weightedTotal }}
      </th>
    @endforeach

  </tr>

  <tr class="total-rank">
  	<th>Rankings</th>

    @foreach($division->choirs as $choir)
    	<th>
        @php $rank = $rankedScores->rank($judge->id)->where('choir_id', $choir->id)->pluck('rank')->first();@endphp
        {{ $rank }}
      </th>
    @endforeach

  </tr>
</table>
</div>
@endif

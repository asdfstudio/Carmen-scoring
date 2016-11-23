@if(!$division->sheet->criteria->isEmpty())
{!! Form::open(array('route' => array('judge.competition.division.round.choir.save_scores',$division->competition,$division,$round,$choir), 'method' => 'post')) !!}
<div class="scorecard">



  @foreach($judge->captions as $caption)

    <div class="caption-container">

      <div class="caption-heading">
        {{ $caption->name }}
      </div>

      @foreach($division->sheet->criteria->where('caption_id', $caption->id) as $criterion)
      <div class="criterion-container" data-criterion-id="{{ $criterion->id }}">
        <div class="criterion" data-criterion-id="{{ $criterion->id }}">
          {{ $criterion->name }}
        </div>

        <div class="score" data-criterion-id="{{ $criterion->id }}">
          <?php $rawScore = $rawScores->where('criterion_id', $criterion->id)->where('judge_id', $judge->id)->where('choir_id', $choir->id)->pluck('score');?>
          <?php $score = $rawScore->first(); ?>
          {{ Form::number("scores[$criterion->id]", $score,['min' => 0, 'max' => 10, 'step' => '0.5', 'data-criterion-id' => $criterion->id]) }}
        </div>

        <div class="number-selector-container">

          @include('scores.forms.number_selector', ['criterion' => $criterion,'score' => $score, 'start' => 1, 'end' => 10, 'interval' => 1])

          @include('scores.forms.number_selector', ['criterion' => $criterion,'score' => $score, 'start' => 1.5, 'end' => 9.5, 'interval' => 1, 'class' => 'half'])

        </div>

      </div>
      @endforeach

    </div>
  @endforeach

  <div class="submit-container">
    {{ Form::submit('Save Scores & Stay',['class' => 'btn btn-primary btn-lg', 'name' => 'save_stay']) }}
    {{ Form::submit('Save Scores & Back to All Choirs',['class' => 'btn btn-primary btn-lg', 'name' => 'save_go']) }}
  </div>

</div>
{!! Form::close() !!}
@endif

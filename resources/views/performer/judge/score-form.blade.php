@if(!$soloDivision->sheet->criteria->isEmpty())
{!! Form::open(array('route' => array('judge.competition.solo-division.performer.score.store', $competition, $soloDivision, $performer), 'method' => 'post', 'class' => 'scorecard autosave')) !!}
<div class="scorecard">



  @foreach($captions as $caption)

    <div class="caption-container">

      <div class="caption-heading">
        {{ $caption->name }}
      </div>

      @foreach($soloDivision->sheet->criteria->where('caption_id', $caption->id) as $criterion)
      <div class="criterion-container" data-criterion-id="{{ $criterion->id }}">
        <div class="criterion" data-criterion-id="{{ $criterion->id }}">
          {{ $criterion->name }}
        </div>

        <div class="criterion-description">
          {{ $criterion->description }}
        </div>



        <div class="score" data-criterion-id="{{ $criterion->id }}">
          @php $rawScore = $rawScores->where('criterion_id', $criterion->id)->pluck('score');@endphp
          @php $score = $rawScore->first(); @endphp
          {{ Form::text("scores[$criterion->id]", $score, ['data-criterion-id' => $criterion->id, 'readonly' => 'readonly', 'required' => 'required', 'data-original-score' => $rawScore, 'class' => 'criterion-score-input']) }}
        </div>

        <div class="number-selector-container">

          @include('scores.forms.number_selector', ['criterion' => $criterion,'score' => $score, 'start' => 1, 'end' => 10, 'interval' => 1])

          @include('scores.forms.number_selector', ['criterion' => $criterion,'score' => $score, 'start' => 0.5, 'end' => 9.5, 'interval' => 1, 'class' => 'half'])

        </div>

      </div>
      @endforeach

    </div>
  @endforeach

  <div class="caption-container">
    <div class="caption-heading">
      Feedback / Comments for Performer
    </div>

    {{ Form::textarea('comment', $comment, ['placeholder' => 'Enter comments/feedback for performer..']) }}
  </div>


  <div class="submit-container">
    {{ Form::submit('Save Scores & Stay',['class' => 'btn btn-primary btn-lg', 'name' => 'save_stay']) }}
    {{ Form::submit('Save Scores & Back to All Performers',['class' => 'btn btn-primary btn-lg', 'name' => 'save_go']) }}
  </div>

</div>
{!! Form::close() !!}
@endif


<div id="autosave-alert-box" class="hide">Autosaving scores...</div>

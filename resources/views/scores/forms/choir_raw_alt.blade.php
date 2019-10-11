@if(!$division->sheet->criteria->isEmpty())
{!! Form::open(array('route' => array('judge.competition.division.round.choir.save_scores',$division->competition,$division,$round,$choir), 'method' => 'post', 'class' => 'scorecard autosave')) !!}
<div class="scorecard">



  @foreach($captions as $caption)

    <div class="caption-container">

      <div class="caption-heading">
        {{ $caption->name }}
      </div>

      @foreach($division->sheet->criteria->where('caption_id', $caption->id) as $criterion)
      <div class="criterion-container" data-criterion-id="{{ $criterion->id }}">
        <div class="criterion" data-criterion-id="{{ $criterion->id }}">
          {{ $criterion->name }}
        </div>

        <div class="criterion-description">
          {{ $criterion->description }}
        </div>



        <div class="score" data-criterion-id="{{ $criterion->id }}">
          @php $rawScore = $rawScores->where('criterion_id', $criterion->id)->where('judge_id', $judge->id)->where('choir_id', $choir->id)->pluck('score');@endphp
          @php $score = $rawScore->first(); @endphp
          {{ Form::text("scores[$criterion->id]", $score, ['data-criterion-id' => $criterion->id, 'readonly' => 'readonly', 'required' => 'required', 'data-original-score' => $rawScore, 'class' => 'criterion-score-input']) }}
        </div>

        <div class="number-selector-container">

          @include('scores.forms.number_selector', ['criterion' => $criterion,'score' => $score, 'start' => 1, 'end' => $criterion->max_score, 'interval' => 1])

          @if(!env('IS_WORKSHOP_ENABLED'))
            @include('scores.forms.number_selector', ['criterion' => $criterion,'score' => $score, 'start' => 0.5, 'end' => ($criterion->max_score - 0.5), 'interval' => 1, 'class' => 'half'])
          @endif

        </div>

      </div>
      @endforeach

    </div>
  @endforeach

  <div class="caption-container">
    <div class="caption-heading">
      Feedback / Comments for Choir
    </div>

    {{ Form::textarea('comment', $comment, ['placeholder' => 'Enter comments/feedback for choir..']) }}
  </div>

  <div class="submit-container">
    {{ Form::submit('Save Scores & Stay',['class' => 'btn btn-primary btn-lg', 'name' => 'save_stay']) }}
    {{ Form::submit('Save Scores & Back to All Choirs',['class' => 'btn btn-primary btn-lg', 'name' => 'save_go']) }}
  </div>

</div>
{!! Form::close() !!}
@endif


<div id="autosave-alert-box" class="hide">Autosaving scores...</div>

@extends('layouts.simple')


@section('content-header')
  @parent
  <h1>Scores for {{ $choir->full_name }} by {{ $judge->full_name }}</hq>
@endsection

@section('content')

  @include('scores.choir_judge_raw',['division' => $round->division,'sheet' => $division->sheet])

  <div class="">
    @php
      $captions = App\Caption::forSheet($division->sheet);

      $judge_criteria_comments = $criterion_comments
                    ->where('judge_id', $judge->id)
                    ->where('subject_id', $round->id);
    @endphp
    <h2>Comments from {{ $judge->full_name }}</h2>
    <h3>Category: {{ $round->name }}</h3>
    <p>{{ $comment }}</p>
    @foreach ($captions as $caption)
      <h5 class="dg-p-12 dg-mb-8 {{ $caption->background_css }}" style="color: #fff; display: inline-block">{{ $caption->name }}</h5>
      @foreach($division->sheet->criteria->where('caption_id', $caption->id) as $criterion)
          @php
              $rawScore = $rawScores->where('criterion_id', $criterion->id)->where('judge_id',$judge->id)->where('choir_id', $choir->id)->pluck('score');
              $score = $rawScore->first()
          @endphp
          <p><b> Criterion: </b> {{ $criterion->name }} - <b>{{ $score }}</b> out of <b>{{ $criterion->max_score }}</b></p>
          @if (count($judge_criteria_comments))
              @foreach ($judge_criteria_comments as $criterion_comment)
                  @if ($criterion_comment->recipient_id === $criterion->id)
                      <p><b> Comment: </b>{{ $criterion_comment->comments }}</p>
                  @endif
              @endforeach
          @endif
          <hr>
      @endforeach
    @endforeach
  </div>

@endsection
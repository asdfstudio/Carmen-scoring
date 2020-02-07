@extends('layouts.public_results')

@section('breadcrumbs')

@endsection

@section('content')

  <h1>{{ $competition->name }} Judge Feedback</h1>
  <h2>{{ $choir->full_name }}</h2>


  <h3>Divisions</h3>

  @foreach($competition->divisions as $div)
    @foreach($div->rounds as $round)
      @php
        $round_comments = $comments->where('subject_id', $round->id)->where('subject_type', 'App\Round');
        $round_recordings = $recordings->where('round_id', $round->id);
      @endphp
      @if($round_comments->count() || $round_recordings->count())
        <h4>{{ $div->name }}, {{ $round->name }}</h4>

        @if(!$round->is_completed)
          <p>Feedback for this round will be available once this round is complete.</p>
        @endif

        @if($round->is_completed)
          @php
            $judges = collect();
            foreach($round_comments as $comment){
               $judges->push($comment->judge);
            }
            foreach($round_recordings as $recording){
               $judges->push($recording->judge);
            }
            $judges = $judges->unique();
          @endphp
          <ul class="list-group">
            @foreach($judges as $judge)
              @php
                $judge_comments = $round_comments->where('judge_id', $judge->id);
                $judge_recordings = $round_recordings->where('judge_id', $judge->id);
                $comments_not_empty = false;
                foreach($judge_comments as $comment){
                  if(!empty($comment->comments)){
                    $comments_not_empty = true;
                  }
                }
              @endphp
              <li class="list-group-item unpadded">
                <div class="header">
                  {{ $judge->full_name }}
                </div>
                <div class="body">
                  <div>
                    @if($comments_not_empty)
                      @foreach($judge_comments as $comment)
                        {!! nl2br($comment->comments) !!}
                      @endforeach
                    @else
                      <i class="text-muted">No typed comments were entered by this judge.</i>
                    @endif
                  </div>
                  @if($competition->organization->is_premium == 1 && $judge_recordings->count())
                    <div class="record-row">
                      <ol>
                        @foreach($judge_recordings as $recording)
                          <li class="record-item">
                            <div class="record-item-audio">
                              <audio controls> <source src="{{$recording->url}}"> </audio>
                              <span> {{$recording->created_at}} (UTC)</span>
                            </div>
                          </li>
                        @endforeach
                      </ol>
                    </div>   
                  @endif            
                </div>
              </li>
            @endforeach
          </ul>
        @endif
      @endif
    @endforeach
  @endforeach

  <h3>Solo Divisions</h3>

  @foreach($competition->soloDivisions as $soloDivision)
      @php
        $solo_comments = $comments->where('subject_id', $soloDivision->id)->where('subject_type', 'App\SoloDivision');
        $solo_recordings = $recordings->where('division_id', $soloDivision->id);
      @endphp
      @if($solo_comments->count() || $solo_recordings->count())
        <h4>{{ $soloDivision->name }}</h4>

        @if(!$soloDivision->is_published)
          <p>Feedback for this round will be available once this round is complete.</p>
        @endif

        @if($soloDivision->is_published)
          @php
            $judges = collect();
            foreach($solo_comments as $comment){
               $judges->push($comment->judge);
            }
            foreach($solo_recordings as $recording){
               $judges->push($recording->judge);
            }
            $judges = $judges->unique();
          @endphp
          <ul class="list-group">
            @foreach($judges as $judge)
              @php
                $judge_comments = $solo_comments->where('judge_id', $judge->id);
                $judge_recordings = $solo_recordings->where('judge_id', $judge->id);
                $comments_not_empty = false;
                foreach($judge_comments as $comment){
                  if(!empty($comment->comments)){
                    $comments_not_empty = true;
                  }
                }
              @endphp
              <li class="list-group-item unpadded">
                <div class="header">
                  {{ $comment->judge->full_name }} - Feedback for {{ $comment->recipient->name }}
                </div>
                <div class="body">
                  <div class="container">
                    <div class="row">
                      @if($comments_not_empty)
                        @foreach($judge_comments as $comment)
                          {!! nl2br($comment->comments) !!}
                        @endforeach
                      @else
                        <i class="text-muted">No typed comments were entered by this judge.</i>
                      @endif
                    </div>
                    @if($competition->organization->is_premium == 1 && $judge_recordings->count())
                      <div class="record-row">
                        <ol>
                          @foreach($judge_recordings as $recording)
                            <li class="record-item">
                              <div class="record-item-audio">
                                <audio controls> <source src="{{$recording->url}}"> </audio>
                                <span> {{$recording->created_at}} (UTC)</span>
                              </div>
                            </li>
                          @endforeach
                        </ol>
                      </div>
                    @endif
                  </div>
                </div>

              </li>
            @endforeach
          </ul>
        @endif
      @endif
  @endforeach
@endsection

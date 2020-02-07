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
                  Judge: {{ $judge->full_name }}
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
                      <h5>Audio Comments:</h5>
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
        
        $judges = collect();
        foreach($solo_comments as $comment){
           $judges->push($comment->judge);
        }
        foreach($solo_recordings as $recording){
           $judges->push($recording->judge);
        }
        $judges = $judges->unique();
      @endphp
      @if($solo_comments->count() || $solo_recordings->count())
        <h4>{{ $soloDivision->name }}</h4>

        @if(!$soloDivision->is_published)
          <p>Feedback for this round will be available once this round is complete.</p>
        @endif

        @if($soloDivision->is_published)
          @foreach($soloDivision->performers as $performer)
            @if(!$solo_comments->where('recipient_id', $performer->id)->count())
              @php continue; @endphp
            @endif
            
            <h5>Feedback for {{ $performer->name }}</h5>
            
            <ul class="list-group" style="padding-left: 20px;">
              @foreach($judges as $judge)
                @php
                  $judge_comments = $solo_comments->where('judge_id', $judge->id)->where('recipient_id', $performer->id);
                  $comments_not_empty = false;
                  foreach($judge_comments as $comment){
                    if(!empty($comment->comments)){
                      $comments_not_empty = true;
                    }
                  }
                @endphp
                @if($comments_not_empty)
                  <li class="list-group-item unpadded">
                    <div class="header">
                      Judge: {{ $judge->full_name }}
                    </div>
                    <div class="body">
                      <div class="container">
                        <div class="row">
                          @foreach($judge_comments as $comment)
                            {!! nl2br($comment->comments) !!}
                          @endforeach
                        </div>
                      </div>
                    </div>

                  </li>
                @endif
              @endforeach
            </ul>
          @endforeach
        @endif
      @endif
  @endforeach
@endsection

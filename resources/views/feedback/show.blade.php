@extends('layouts.public_results')

@section('breadcrumbs')

@endsection

@section('content')

  <h1>{{ $competition->name }} Judge Feedback</h1>
  <h2>{{ $choir->full_name }}</h2>


  <h3>Divisions</h3>

  @foreach($competition->divisions as $div)

    @foreach($div->rounds as $round)


      @if($comments->where('subject_id', $round->id)->where('subject_type', 'App\Round')->count())
        <h4>{{ $div->name }}, {{ $round->name }}</h4>

        @if(!$round->is_completed)
          <p>Feedback for this round will be available once this round is complete.</p>
        @endif

        @if($round->is_completed)
          <ul class="list-group">
            @foreach($comments->where('subject_id', $round->id)->where('subject_type', 'App\Round') as $comment)
              <li class="list-group-item unpadded">
                <div class="header">
                {{$comment->choir_id}}
                  {{ $comment->judge->full_name }}
                </div>
                <div class="body">
                <div class="container">
                  <div class="row">
                    @if($comment->comments)
                      {!! nl2br($comment->comments) !!}
                    @else
                      <i class="text-muted">No typed comments were entered by this judge</i>
                    @endif
                  </div>
                  @if($competition->organization->is_premium == 1)
                  <div class="row wrap record-row">
                  @foreach($comment->recordings as $key => $recording)
                    <div class="col-sm-6 record-item">
                      <span class="record-span">{{$key + 1}}.</span>
                      <div class="record-item-audio">
                        <audio controls> <source src="{{$recording->url}}"> </audio>
                        <span> {{$recording->created_at}} (UTC)</span>
                      </div>
                    </div>
                  @endforeach
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


      @if($comments->where('subject_id', $soloDivision->id)->where('subject_type', 'App\SoloDivision')->count())
        <h4>{{ $soloDivision->name }}</h4>

        @if(!$soloDivision->is_published)
          <p>Feedback for this round will be available once this round is complete.</p>
        @endif

        @if($soloDivision->is_published)
          <ul class="list-group">
            @foreach($comments->where('subject_id', $soloDivision->id)->where('subject_type', 'App\SoloDivision') as $comment)
              <li class="list-group-item unpadded">
                <div class="header">
                  {{ $comment->judge->full_name }} - Feedback for {{ $comment->recipient->name }}
                </div>
                <div class="body">
                  <div class="container">
                    <div class="row">
                    @if($comment->comments)
                      {!! nl2br($comment->comments) !!}
                    @else
                      <i class="text-muted">No typed comments were entered by this judge</i>
                    @endif
                    </div>
                    <div class="row wrap record-row">
                      @if($competition->organization->is_premium == 1)
                    @foreach($comment->recordings as $recording)
                      <div class="col-sm-6 record-item">
                        <audio controls> <source src="{{$recording->url}}"> </audio>
                      </div>
                    @endforeach
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

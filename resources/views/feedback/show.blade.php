@extends('layouts.public_results')

@section('breadcrumbs')

@endsection

@section('content')

  <h1>{{ $competition->name }} Judge Feedback</h1>
  <h2>{{ $choir->full_name }}</h2>


  @foreach($competition->divisions as $div)

    @foreach($div->rounds as $round)


      @if($comments->where('subject_id', $round->id)->where('subject_type', 'App\Round')->count())
        <h3>{{ $div->name }}, {{ $round->name }}</h3>

        @if(!$round->is_completed)
          <p>Feedback for this round will be available once this round is complete.</p>
        @endif

        @if($round->is_completed)
          <ul class="list-group">
            @foreach($comments->where('subject_id', $round->id)->where('subject_type', 'App\Round') as $comment)
              <li class="list-group-item unpadded">
                <div class="header">
                  {{ $comment->judge->full_name }}
                </div>
                <div class="body">
                  {{ $comment->comments }}
                </div>

              </li>
            @endforeach
          </ul>
        @endif

      @endif
    @endforeach
  @endforeach



@endsection

@extends('layouts.simple')

@section('content-header')
  <h1>Recordings</h1>
@endsection


@section('content')

  <a id="next_link" class="btn button" href="{{ $next_link }}">Run Next Batch</a>
  <script>
    var autoAdvance = setTimeout(function(){ $('#next_link').trigger('click'); }, 1000);
    $(document.body).click(function(){
      clearTimeout(autoAdvance);
    });
  </script>

  <pre style="padding: 0 0 0 40px;">

    <ul>

    @foreach($recordings as $recording)

      <li>Name: {{ $recording['name'] }}<br>MIME Type: {{ $recording['mime_type'] }}<br>URL: {{ $recording['url'] }}</li>

    @endforeach

    </ul>

  </pre>

@endsection

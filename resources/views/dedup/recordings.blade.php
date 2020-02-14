@extends('layouts.simple')

@section('content-header')
  <h1>Recordings</h1>
@endsection


@section('content')

  <a id="next_link" class="btn button" href="{{ $next_link }}">Run Next Batch</a>

  <pre style="padding: 0 0 0 40px;">

    <ul>

    @foreach($recordings as $recording)

      <li>Name: {{ $recording['name'] }}<br>MIME Type: {{ $recording['mime_type'] }}<br>Size: {{ $recording['size'] }}<br>URL: {{ $recording['url'] }}</li>

    @endforeach

    </ul>

  </pre>

@endsection

@section('body-footer')
 <script>
  $(document).ready(function(){
    var autoAdvance = setTimeout(function(){
      window.location = $('#next_link').attr('href');
    }, 1000);
    $(document.body).click(function(){
      clearTimeout(autoAdvance);
    });
  });
</script>
@endsection

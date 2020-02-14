@extends('layouts.simple')

@section('content-header')
  <h1>Recordings</h1>
@endsection


@section('content')

  <pre style="padding: 0 0 0 40px;">

    <ul>

    @foreach($recordings as $recording)

      <li>Name: {{ $recording['name'] }}<br>MIME Type: {{ $recording['mime_type'] }}<br>URL: {{ $recording['url'] }}</li>

    @endforeach

    </ul>

  </pre>

@endsection

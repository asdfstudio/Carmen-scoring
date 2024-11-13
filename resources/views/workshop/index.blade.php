@extends('layouts.simple')

@section('content-header')
  <h1>Workshop Management</h1>
@endsection


@section('content')

  <div class="row">
    <div class="col-md-4">
      <h2>Open Scoring</h2>
      <ul>
        <li>Activates all competitions.</li>
        <li>Activates scoring all classes.</li>
        <li>Activates scoring for all types.</li>
      </ul>
      {{ link_to_route('workshop.open', 'Open Scoring', [], ['class' => 'btn btn-default'])}}
    </div>

    <div class="col-md-4">
      <h2>Close Scoring</h2>
      <ul>
        <li>Complete scoring for all types.</li>
        <li>Complete scoring all classes.</li>
      </ul>
      {{ link_to_route('workshop.close', 'Close Scoring', [], ['class' => 'btn btn-default'])}}
    </div>

    <div class="col-md-4">
      <h2>Finalize Scoring</h2>
      <ul>
        <li>Finalize class scoring.</li>
        <li>Complete competitions.</li>
      </ul>
      {{ link_to_route('workshop.finalize', 'Finalize Scoring', [], ['class' => 'btn btn-default'])}}
    </div>
  </div>

@endsection

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
        <li>Activates scoring all divisions.</li>
        <li>Activates scoring for all rounds.</li>
      </ul>
      {{ link_to_route('workshop.open', 'Open Scoring', [], ['class' => 'btn btn-default'])}}
    </div>

    <div class="col-md-4">
      <h2>Close Scoring</h2>
      <ul>
        <li>Complete scoring for all rounds.</li>
        <li>Complete scoring all divisions.</li>
      </ul>
      {{ link_to_route('workshop.close', 'Close Scoring', [], ['class' => 'btn btn-default'])}}
    </div>

    <div class="col-md-4">
      <h2>Finalize Scoring</h2>
      <ul>
        <li>Finalize division scoring.</li>
        <li>Complete competitions.</li>
      </ul>
      {{ link_to_route('workshop.finalize', 'Finalize Scoring', [], ['class' => 'btn btn-default'])}}
    </div>
  </div>

@endsection

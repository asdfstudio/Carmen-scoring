

@extends('layouts.public_results')



@section('content')

  @if ($access_code)
    <p class="alert alert-danger">
      The access code you entered, {{ $access_code }}, does not match the solo division you are trying to view.
    </p>
  @endif


  <div class="alert alert-info">
    <h3>Particants - Access Full Results</h3>
    <ol>
      <li>Enter the access code for this division that was provided by your competition.</li>
    </ol>

    {!! form($accessCodeForm) !!}
  </div>



@endsection

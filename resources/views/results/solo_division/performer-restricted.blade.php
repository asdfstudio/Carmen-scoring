

@extends('layouts.public_results')



@section('content')

  @if($director_email)
    <p class="alert alert-danger">
      The email address you entered, {{ $director_email }}, does not match the solo division performer you are trying to view.
    </p>
  @endif
  
  <div class="alert alert-info">
    <h3>Participants - Access Full Results</h3>
    <ol>
      <li>Enter the email address on file for the director of your ensemble.</li>
    </ol>

    {!! form($accessCodeForm) !!}
  </div>



@endsection

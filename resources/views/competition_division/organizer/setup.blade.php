@extends('layouts.simple')

@section('content-header')
  <h1>Set Up Competition Classes</h1>
@endsection

@section('content')



  @include('competition_division.organizer.table',['divisions' => $competition->divisions])

  {!! form_start($form) !!}
    <div class="collection-container" data-prototype="{{ form_row($form->divisions->prototype()) }}">
        {!! form_row($form->divisions) !!}
    </div>
    <button type="button" class="add-to-collection btn btn-secondary">Add class</button>
    {!! form_end($form) !!}

@endsection

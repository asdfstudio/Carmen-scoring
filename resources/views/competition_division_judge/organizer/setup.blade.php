@extends('layouts.app')

@section('content')

  {!! form_start($form) !!}
    <div class="collection-container" data-prototype="{{ form_row($form->judges->prototype()) }}">
      {!! form_row($form->judges) !!}
    </div>
    <button type="button" class="add-to-collection btn btn-secondary">Add judge</button>
  {!! form_end($form) !!}

@endsection

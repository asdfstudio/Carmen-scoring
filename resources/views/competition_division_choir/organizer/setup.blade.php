@extends('layouts.simple')

@section('content')

  {!! form_start($form) !!}
    <div class="collection-container" data-prototype="{{ form_row($form->choirs->prototype()) }}">
      {!! form_row($form->choirs) !!}
    </div>
    <button type="button" class="add-to-collection btn btn-secondary">Add choir</button>
  {!! form_end($form) !!}




@endsection

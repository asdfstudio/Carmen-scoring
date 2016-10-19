@extends('layouts.app')


@section('content')

  {!! form_start($form) !!}
    <div class="collection-container" data-prototype="{{ form_row($form->rounds->prototype()) }}">
      {!! form_row($form->rounds) !!}
    </div>
    <button type="button" class="add-to-collection btn btn-secondary">Add round</button>
  {!! form_end($form) !!}

@endsection

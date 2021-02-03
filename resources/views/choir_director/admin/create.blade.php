@extends('layouts.simple')

@section('content-header')
    <h1>{{ $choir->full_name }}</h1>

    <ul class="actions-group">
        <li>{{ link_to_route('admin.choir.index','Back to All Choirs', [], ['class' => 'action']) }}</li>
    </ul>
@endsection

@section('content')

    <h2>Add a Director</h2>

    {!! form($form) !!}

@endsection

@section('body-footer')
    <script src="/dist/js/director-form.js"> </script>
@endsection

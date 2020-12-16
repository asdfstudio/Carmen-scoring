@extends('layouts.simple')


@section('content-header')
    <h1>{{ $soloDivision->name }}</h1>

    <ul class="actions-group">
        <li>{{ link_to_route('organizer.competition.solo-division.show', 'Back to Solo Division', [$competition, $soloDivision], ['class' => 'action']) }}</li>
    </ul>
@endsection

@section('content')

    <h2>Manage Judges</h2>

    {!! form_start($form) !!}
    <div class="collection-container" data-prototype="{{ form_row($form->judges->prototype()) }}">
        {!! form_row($form->judges) !!}
    </div>
    {!! form_end($form) !!}

@endsection

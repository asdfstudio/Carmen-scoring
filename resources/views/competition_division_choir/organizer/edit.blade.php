@extends('layouts.simple')

@section('content')
    <ul class="actions-group" style="float: right">
        <li>{{ link_to_route('organizer.competition.division.show', 'Back to the Class', [$division->competition, $division], ['class' => 'action']) }}
        </li>
    </ul>
    <h1>Competition > Classes > Ensembles > {{ $choir->name }}</h1>


    @include('choir.partial.single')
    @include('competition_division_choir.organizer.update', ['form' => $form])
@endsection

@extends('layouts.simple')

@section('style')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css"/>
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('organizer.competition.division.create',$competition) !!}
@endsection

@section('content-header')
    @isset($updating) <h1>Edit a division</h1> @else <h1>Create a division</h1> @endisset

    <ul class="actions-group">
        <li>{{ link_to_route('organizer.competition.division.index','Back to All Divisions',[$competition],['class' => 'action dg-back-all-divisions']) }}</li>
    </ul>
@endsection

@section('content')
    <div class="update-division-content">
        {!! form_start($form) !!}

        {!! form_until($form, 'rating_system_heading') !!}

        <div class="rating-system collection-container form-group" data-prototype="{{ form_row($form->rating_system->prototype()) }}">
            {!! form_row($form->rating_system) !!}
        </div>

        {!! form_end($form) !!}
        @isset($updating)
        @can('destroy', $division)
            <hr>
            <h3>Delete this division?</h3>
            <p class="alert alert-danger d-flex"><i class="fa fa-exclamation-triangle dg-fs-22 mr"></i>This is a permanent, irrecoverable action. Proceed with caution.</p>
            {!! form($deleteForm) !!}
        @endcan
    @endif
    </div>

@endsection

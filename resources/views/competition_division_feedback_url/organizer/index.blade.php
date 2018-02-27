@extends('layouts.simple')

@section('breadcrumb')

@endsection

@section('content-header')
	<h1>Feedback Links</h1>

	{{ link_to_route('organizer.competition.show', 'Back to Competition', [$competition], ['class' => 'action']) }}

@endsection

@section('content')

  @include('feedback-url.organizer.table',['commentUrls' => $competition->commentUrls])

@endsection

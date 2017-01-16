@extends('layouts.simple')

@section('content-header')
	<h1>Awards</h1>

	<ul class="actions-group">
		@can('create' , ['App\Award'])
		  <li>{{ link_to_route('organizer.award.create','Add an award',NULL,['class' => 'action']) }}</li>
		@endcan

	</ul>
@endsection

@section('content')

	<p class="content-intro">
		This pages lists all of your award options. There are standard Carmen Scoring System awards and custom awards that you can create for your organization. For each division of your competition, you can choose which of these awards you'd like to give out.
	</p>

	<h2>Custom Awards</h2>
	<p class="content-intro">
		Custom awards are created and used by your organization. They can be used in as many competitions and divisions as you'd like.
	</p>
  @include('award.organizer.list')

	<h2>Standard Awards</h2>
	<p class="content-intro">
		Standard awards are the default awards that were created by Carmen Scoring. If an award doesn't exist here, you can create a custom award for your organization.
	</p>
  @include('award.organizer.list', ['awards' => $standard_awards])

@endsection

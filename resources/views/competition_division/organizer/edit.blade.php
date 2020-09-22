@extends('layouts.simple')

@section('style')
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css"/>
@endsection

@section('breadcrumbs')
  {!! Breadcrumbs::render('organizer.competition.division.edit',$competition,$division) !!}
@endsection

@section('content-header')
  <h1>Edit a division</h1>

  <ul class="actions-group">
		<li>{{ link_to_route('organizer.competition.division.settings','Back to Settings',[$competition, $division],['class' => 'action']) }}</li>
	</ul>
@endsection

@section('content')

  <div class="edit-division-content">
		{!! form_start($form) !!}
      
      {!! form_until($form, 'rating_system_heading') !!}
      
      <div class="rating-system collection-container form-group" data-prototype="{{ form_row($form->rating_system->prototype()) }}">
        {!! form_row($form->rating_system) !!}
      </div>
      
		{!! form_end($form) !!}

    @can('destroy', $division)
      <hr>

      <h3>Delete this division?</h3>
      <p class="alert alert-danger d-flex"><i class="fa fa-exclamation-triangle dg-fs-22 mr"></i>This is a permanent, irrecoverable action. Proceed with caution.</p>
      {!! form($deleteForm) !!}
    @endcan
  </div>

  <div class="d-none all-sheets-detail-wrapper">
		@foreach ($sheets as $sheet)
			<div class="sheet-detail-wrapper sheet-id-{{$sheet->id}} text-left">
				<h1 class="text-center">{{ $sheet->name }}</h1>

				<h4 class="text-center">Criteria: {{ count($sheet->criteria) }}</h4>
				<h4 class="text-center">Total Points Available: {{ $sheet->max_score }}</h4>
				<h4 class="text-center">Total Weighted Points Available: {{ $sheet->weighted_max_score }}</h4>

				@foreach ($sheet->captions as $caption)
					<h2>{{ $caption->name }}</h2>
					
					@php
						$criteria = $sheet->criteria->where('caption_id', $caption->id);
					@endphp
					
					@if($criteria->isEmpty())
						<p>There are no criteria.</p>
					@endif

					@if(!$criteria->isEmpty())
					<ul class="list-group">
						@foreach($criteria as $criterion)
							<li class="school list-group-item">

								<div class="name">
									{{ $criterion->name }}
									<div class="pull-right label">Max score: {{ $criterion->max_score }}</div>
								</div>
								<div class="description mv">{{ $criterion->description }}</div>

							</li>
						@endforeach
					</ul>
					@endif
				@endforeach
			</div>
		@endforeach
  </div>
  
@endsection

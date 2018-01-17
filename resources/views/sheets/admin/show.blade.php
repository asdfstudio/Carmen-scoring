@extends('layouts.simple')

@section('content-header')
  <h1>{{ $sheet->name }}</h1>

  <ul class="actions-group">
    <li>{{ link_to_route('admin.sheet.index', 'Back to sheets', [], ['class' => 'action']) }}</li>
    <li>{{ link_to_route('admin.sheet.edit', 'Edit sheet', [$sheet], ['class' => 'action']) }}</li>
    <li>{{ link_to_route('admin.sheet.manage', 'Manage criteria', [$sheet], ['class' => 'action']) }}</li>
  </ul>



@endsection

@section('content')

    <h4>Total Points Available: {{ $sheet->max_score }}</h4>
    <h4>Total Weighted Points Available: {{ $sheet->weighted_max_score }}</h4>

		@foreach ($sheet->captions as $caption)
      <h2>{{ $caption->name }}</h2>



      @include('criteria.admin.list-simple', ['criteria' => $sheet->criteria->where('caption_id', $caption->id) ])
    @endforeach

@endsection

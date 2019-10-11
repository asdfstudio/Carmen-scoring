@extends('layouts.simple')

@section('content-header')
  <h1>Manage sheet criteria display order: {{ $sheet->name }}</h1>

	{{ link_to_route('admin.sheet.index', 'Back to sheets', [], ['class' => 'action'])}}
@endsection

@section('content')

    {{ Form::open() }}

    @foreach ($captions as $caption)
      <h2>{{ $caption->name }}</h2>
      @php $filteredCriteria = $sheet->criteria->where('caption_id', $caption->id); @endphp

      @if ($filteredCriteria->count() > 0)
        @include('criteria.admin.order-choices', ['criteria' => $filteredCriteria])
      @else
        <p>No criteria selected for this caption.</p>
      @endif

    @endforeach

    {{ Form::submit('Save Criteria Order', ['class' => 'action']) }}

    {{ Form::close() }}

@endsection

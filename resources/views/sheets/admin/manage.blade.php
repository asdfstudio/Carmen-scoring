@extends('layouts.simple')

@section('content-header')
  <h1>Manage sheet criteria: {{ $sheet->name }}</h1>

	{{ link_to_route('admin.sheet.index', 'Back to sheets', [], ['class' => 'action'])}}
@endsection

@section('content')

    {{ Form::open() }}

    @foreach ($captions as $caption)
      <h2>{{ $caption->name }}</h2>
      @php $filteredCriteria = $criteria->where('caption_id', $caption->id); @endphp
      @include('criteria.admin.choices', ['criteria' => $filteredCriteria, 'selectedCriteria' => $sheet->criteria])
    @endforeach

    {{ Form::submit('Save Criteria', ['class' => 'action']) }}

    {{ Form::close() }}

@endsection

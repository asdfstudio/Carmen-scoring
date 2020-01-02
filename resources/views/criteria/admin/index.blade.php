@extends('layouts.simple')

@section('content-header')
  <h1>Criteria</h1>

	{{ link_to_route('admin.criteria.create', 'Add a criterion', [], ['class' => 'action']) }}
@endsection

@section('content')

  @foreach ($captions as $caption)
    <h2>{{ $caption->name }}</h2>
    @php $filteredCriteria = $criteria->where('caption_id', $caption->id); @endphp
    @include('criteria.admin.list', ['criteria' => $filteredCriteria])
  @endforeach


@endsection

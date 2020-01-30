@extends('layouts.simple')

@section('content-header')
  <h1>Manage sheet caption display order: {{ $sheet->name }}</h1>

	{{ link_to_route('admin.sheet.index', 'Back to sheets', [], ['class' => 'action'])}}
@endsection

@section('content')

    {{ Form::open() }}

    @if(!$captions->isEmpty())
    <ul class="list-group">
      @foreach($captions as $caption)
        <li class="school list-group-item choice">

          @php
          if (is_array($sheet->caption_sort_order)) {
            $index = array_search($caption->id, $sheet->caption_sort_order);
          } else {
            $index = false;
          }


          if ($index !== false) {
            $position = $index + 1;
          } else {
            $position = '';
          }

          @endphp
          <div class="input-container">
            {{ Form::text('captions['.$caption->id.']', $position) }}
          </div>

          <div class="">
            <div class="name">
      				{{ $caption->name }}
      			</div>
          </div>
        </li>
      @endforeach
    </ul>
    @endif


    {{ Form::submit('Save Caption Order', ['class' => 'action']) }}

    {{ Form::close() }}

@endsection

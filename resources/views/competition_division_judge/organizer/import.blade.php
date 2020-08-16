@extends('layouts.simple')

@section('content-header')
  <h1>Import Judges to Division</h1>

  <ul class="actions-group">
    <li>
      {{ link_to_route('organizer.competition.division.judge.index','Back to judges', [$division->competition->id, $division->id], ['class' => 'action']) }}
    </li>
  </ul>
@endsection


@section('content')
  @if (count($divisions) == 0)
    <div>There are no divisions</div>
  @else
    
    {{ Form::open() }}
      <label for="id" class="control-label">Choose a division to import judges from</label>

      @foreach($divisions as $division_other)
        @php
        $judges = $division_other->judges->unique('id')->pluck('full_name');
        $judges_list = implode(', ',$judges->toArray());
        @endphp
        <div class="choice-container">
          {{ Form::radio('id', $division_other->id, NULL, ['id' => 'id_'.$division_other->id]) }}

          <label for="id_{{ $division_other->id }}">
            <strong>{{ $division_other->name }}</strong> <br />
            {{ $judges_list }}
          </label>
        </div>
      @endforeach

      {{ Form::submit('Import Judges', ['class' => 'btn btn-primary']) }}
    {{ Form::close() }}
  @endif
@endsection

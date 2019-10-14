@extends('layouts.simple')


@section('content-header')
	<h1>{{ $soloDivision->name }}</h1>

	<ul class="actions-group">
    <li>{{ link_to_route('organizer.competition.solo-division.show', 'Back to Solo Division', [$competition, $soloDivision], ['class' => 'action']) }}</li>
	</ul>
@endsection

@section('content')


    <h2>Performers</h2>

    {{ Form::open() }}
    <table class="table table-striped table-bordered">
      <tr>
        <th>Choir</th>
        <th>Category</th>
        <th>Name</th>
      </tr>

    @php $i = 0; @endphp
    @while ($i < $soloDivision->max_performers)
      @php
      $performer = $soloDivision->performers->slice($i, 1)->first();

      if ($performer) {
        $performerId = $performer->id;
        $choirId = $performer->choir_id;
        $category = $performer->category;
        $name = $performer->name;
      } else {
        $performerId = null;
        $choirId = null;
        $category = null;
        $name = null;
      }
      @endphp
      <tr>
        {{ Form::hidden('performer['.$i.'][id]', $performerId) }}
        <td>{{ Form::select('performer['.$i.'][choir_id]', $choirs, $choirId, ['placeholder' => 'Select', 'class' => 'selectize']) }}</td>
        <td>{{ Form::select('performer['.$i.'][category]', [1 => $soloDivision->category_1, 2 => $soloDivision->category_2], $category, ['placeholder' => 'Select', 'class' => 'selectize']) }}</td>
        <td>{{ Form::text('performer['.$i.'][name]', $name, ['class' => 'form-control']) }}</td>

      </tr>
      @php $i++; @endphp
    @endwhile

    </table>

    {{ Form::submit('Save Performers', ['class' => 'action']) }}

    {{ Form::close() }}

@endsection

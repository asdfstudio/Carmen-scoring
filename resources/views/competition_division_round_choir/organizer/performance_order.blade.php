@extends('layouts.simple')

@section('content-header')
	<h1>Manage Choir Performance Order</h1>

	<ul class="actions-group">
		@can('create',['App\Round',$division])
			<li>
				{{ link_to_route('organizer.competition.division.round.index','Back to all rounds',[$division->competition,$division], ['class' => 'action']) }}
			</li>
		@endcan

	</ul>

@endsection

@section('content')

	<p>Drag and drop the choirs to change the performance order. The top of the list is the first performer and bottom of list is the final performer.</p>

	{{ Form::open(['method' => 'POST']) }}

	<ul class="list-group sortable-list" id="sortable-list">
		@foreach($choirs as $choir)
			<li class="list-group-item" data-id="{{ $choir->id }}">
				{{ $choir->full_name }}

				{{ Form::hidden('performance_order['.$choir->id.']', $choir->pivot->performance_order, ['id' => 'input-choir-'.$choir->id]) }}
			</li>
		@endforeach
	</ul>

	{{ Form::submit('Save Order', ['class' => 'btn btn-primary']) }}

	{{ Form::close() }}

  <!-- CDNJS :: Sortable (https://cdnjs.com/) -->
<script src="//cdnjs.cloudflare.com/ajax/libs/Sortable/1.4.2/Sortable.js"></script>

<script type="text/javascript">
  var el = document.getElementById('sortable-list');
  var sortable = Sortable.create(el, {
		onEnd: function(e) {
			var order = sortable.toArray();
			order.forEach(function(choir_id, index) {
				document.getElementById('input-choir-'+choir_id).value = index;
			});
		}
  });
</script>

@endsection

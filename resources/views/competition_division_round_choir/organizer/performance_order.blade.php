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

	<div class="alert alert-info">
		<p>Drag and drop the choirs to change the performance order.</p>

		<p>The top of the list is the first performer and bottom of list is the final performer.</p>
	</div>


	{{ Form::open(['method' => 'POST']) }}

	<ul class="list-group sortable-list" id="sortable-list">
		@foreach($choirs as $index => $choir)
			<li class="list-group-item choir" data-id="{{ $choir->id }}">
				<span class="sort-handle"><i class="fa fa-sort"></i></span>
				<span class="performance-order" id="performance-order-{{ $choir->id }}">{{ $index + 1 }}</span>

				@if($choir->school)
					<span class="school">{{ $choir->school->name }}</span>
				@endif

				<span class="name">{{ $choir->name }}</span>

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
		handle: '.sort-handle',
		onEnd: function(e) {
			var order = sortable.toArray();
			order.forEach(function(choir_id, index) {
				var index_base_1 = index + 1;
				document.getElementById('input-choir-'+choir_id).value = index;
				document.getElementById('performance-order-'+choir_id).innerHTML = index_base_1;
			});
		}
  });
</script>

@endsection

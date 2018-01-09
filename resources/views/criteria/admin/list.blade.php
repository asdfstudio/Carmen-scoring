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

			<span class="label small count">{{ $criterion->sheets->count() }} Sheets</span>
			<div class="description mv">{{ $criterion->description }}</div>

      <ul class="actions-group">
        <li>{{ link_to_route('admin.criteria.edit', 'Edit Criterion', [$criterion], ['class' => 'action']) }}</li>

				@foreach ($criterion->sheets as $sheet)
					<li>{{ link_to_route('admin.sheet.manage', 'Manage ' . $sheet->name, [$sheet], ['class' => 'action']) }}</li>
				@endforeach
      </ul>

    </li>
  @endforeach
</ul>
@endif

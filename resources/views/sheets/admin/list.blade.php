@if($sheets->isEmpty())
	<p>There are no criteria.</p>
@endif

@if(!$sheets->isEmpty())
<ul class="list-group">
  @foreach($sheets as $sheet)
    <li class="school list-group-item {{ $sheet->is_retired ? 'retired-sheet' : '' }}">

      <span class="name">{{ $sheet->name }}</span>
			<ul class="list-group">
				<li class="list-group-item">Criteria: {{ $sheet->criteria()->count() }}</li>
				<li class="list-group-item">Total Points Available: {{ $sheet->max_score }} ({{ $sheet->weighted_max_score }} if using Weighted Scoring)</li>
			</ul>

      <ul class="actions-group">
        <li>{{ link_to_route('admin.sheet.show', 'View', [$sheet], ['class' => 'action']) }}</li>
				<li>{{ link_to_route('admin.sheet.edit', 'Edit', [$sheet], ['class' => 'action']) }}</li>
				<li>{{ link_to_route('admin.sheet.manage', 'Manage Criteria', [$sheet], ['class' => 'action']) }}</li>
				<li>{{ link_to_route('admin.sheet.manage-order', 'Manage Criteria Display Order', [$sheet], ['class' => 'action']) }}</li>
				<li>{{ link_to_route('admin.sheet.manage-caption-order', 'Manage Caption Display Order', [$sheet], ['class' => 'action']) }}</li>
      </ul>



    </li>
  @endforeach
</ul>
@endif

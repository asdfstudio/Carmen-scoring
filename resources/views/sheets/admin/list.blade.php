@if($sheets->isEmpty())
	<p>There are no criteria.</p>
@endif

@if(!$sheets->isEmpty())
<ul class="list-group">
  @foreach($sheets as $sheet)
    <li class="school list-group-item">

      <span class="name">{{ $sheet->name }}</span>
			<span class="description">Criteria: {{ $sheet->criteria->count() }}</span>

      <ul class="actions-group">
        <li>{{ link_to_route('admin.sheet.show', 'View', [$sheet], ['class' => 'action']) }}</li>
				<li>{{ link_to_route('admin.sheet.edit', 'Edit', [$sheet], ['class' => 'action']) }}</li>
				<li>{{ link_to_route('admin.sheet.manage', 'Manage Criteria', [$sheet], ['class' => 'action']) }}</li>
      </ul>



    </li>
  @endforeach
</ul>
@endif

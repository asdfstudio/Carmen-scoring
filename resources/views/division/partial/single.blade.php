
<ul class="list-group">
	@if($division->captionWeighting)
		<li class="list-group-item">Caption Weighting: {{ $division->captionWeighting->name }}</li>
	@endif

	@if($division->scoringMethod)
  	<li class="list-group-item">Scoring Method: {{ $division->scoringMethod->name }}</li>
	@endif

	@if($division->sheet)
  	<li class="list-group-item">Scoring Sheet: {{ $division->sheet->name }}</li>
	@endif
</ul>

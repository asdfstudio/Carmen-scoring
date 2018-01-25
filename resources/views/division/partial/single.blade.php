
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

	<!--<li class="list-group-item">Overall Awards: {{ $division->overall_award_count }}</li>

	<li class="list-group-item">Music Awards: {{ $division->music_award_count }}</li>
	<li class="list-group-item">Show Awards: {{ $division->show_award_count }}</li>
	<li class="list-group-item">Combo Awards: {{ $division->combo_award_count }}</li>-->
</ul>

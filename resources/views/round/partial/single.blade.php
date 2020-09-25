
<ul class="list-group">
	@if($round->captionWeighting)
		<li class="list-group-item">
			Caption Weighting: <div class="dg-fs-24 dg-m-8">{{ $round->captionWeighting->name }}</div>
		</li>
	@endif

	@if($round->scoringMethod)
  	<li class="list-group-item">
			Scoring Method: <div class="dg-fs-24 dg-m-8">{{ $round->scoringMethod->name }}</div>
		</li>
	@endif

	@if($round->sheet)
  	<li class="list-group-item">
			Scoring Sheet: <div class="dg-fs-24 dg-m-8">{{ $round->sheet->name }}</div>
		</li>
	@endif

	<!--<li class="list-group-item">Overall Awards: {{ $round->overall_award_count }}</li>

	<li class="list-group-item">Music Awards: {{ $round->music_award_count }}</li>
	<li class="list-group-item">Show Awards: {{ $round->show_award_count }}</li>
	<li class="list-group-item">Combo Awards: {{ $round->combo_award_count }}</li>-->
</ul>

@if($rawScoreFiles->isEmpty())
	<p>There are no criteria.</p>
@endif

@if(!$rawScoreFiles->isEmpty())
<ul class="list-group">
  @foreach($rawScoreFiles as $rawScoreFile)
    <li class="school list-group-item">

      <span class="name">{{ $rawScoreFile['date'] }}</span>

      <ul class="actions-group">
        <li>{{ link_to_route('admin.raw-score-log.show', 'View', [$rawScoreFile['date']], ['class' => 'action']) }}</li>
      </ul>



    </li>
  @endforeach
</ul>
@endif

@if($round->is_scoring_active)
  <div class="alert alert-success">Scoring is currently active for this round</div>
@endif
  
@if(! $round->is_scoring_active)
  @if($round->is_completed)
    <div class="alert alert-warning">Scoring is complete for this round. {{ link_to_route('round.scores','View aggregate scores',[$round->division->competition,$round->division,$round]) }}</div>
  @else
    <div class="alert alert-warning">Scoring is not active for this round</div>
  @endif
@endif
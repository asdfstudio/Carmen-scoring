@if($division->standing == false)
  <p>
    There are no final standings yet.
  </p>
@endif

@if($division->standing)
  @if($division->standing->is_consensus_scoring)
    <p class="alert alert-warning">
      Consensus scoring is used for this division.
    </p>
  @endif

  @include('standing.list', ['standing' => $division->standing])

@endif

@if($division->standing == false)
  <p>
    There are no final standings yet.
  </p>
@endif



@if($division->standing)

  @can('viewFinalStandings', $division)

    @if($division->standing->is_consensus_scoring)
      <p class="alert alert-warning">
        Consensus scoring is used for this division.
      </p>
    @endif

    @include('standing.list', ['standing' => $division->standing])

  @endcan

  @cannot('viewFinalStandings', $division)
    <p>
      You will be able to view the standings once they are finalized.
    </p>
  @endcannot

@endif

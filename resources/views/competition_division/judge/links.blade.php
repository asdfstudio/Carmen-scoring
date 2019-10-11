@if($divisions)

  <h3 class="heading">Divisions</h3>

  <ul class="list-group">
    @foreach($divisions as $div)
        @php
        $active_class = false;
        if(isset($division) AND $division->id == $div->id)
        {
          $active_class = 'active';
        }
        @endphp
        @php $anchor = $div->name;@endphp
        {!! link_to_route('judge.competition.division.show', $anchor, [$div->competition_id,$div],['class' => 'list-group-item '.$active_class]) !!}
    @endforeach

  </ul>
@endif



@if($judges AND $choir)

  <h3>Scores for {{ $choir->name }} By Judge</h3>

  <ul class="list-group">
    {{ link_to_route('organizer.competition.division.round.choir.show', 'All Judges', [$division->competition_id,$division,$round,$choir],['class' => 'list-group-item'])}}
    @foreach($judges as $judge)
      <!--<li class="list-group-item">-->
        {{ link_to_route('organizer.competition.division.round.choir.judge.show', $judge->full_name, [$division->competition_id,$division,$round,$choir,$judge],['class' => 'list-group-item'])}}
      <!--</li>-->
    @endforeach
  </ul>

@endif



@if($choirs AND $judge)

  <h3>Scores from {{ $judge->full_name }} By Choir</h3>

  <ul class="list-group">
    {{ link_to_route('organizer.competition.division.round.judge.show', 'All Choirs', [$division->competition_id,$division,$round,$judge],['class' => 'list-group-item'])}}
    @foreach($choirs as $choir)
      <!--<li class="list-group-item">-->
        {{ link_to_route('organizer.competition.division.round.choir.judge.show', $choir->name, [$division->competition_id,$division,$round,$choir,$judge],['class' => 'list-group-item'])}}
      <!--</li>-->
    @endforeach
  </ul>

@endif

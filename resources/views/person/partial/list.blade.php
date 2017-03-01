@if($people)
  <ul class="list-group">
    @foreach($people as $person)
      <li class="list-group-item person">
        <span class="name">{{ $person->full_name }}</span>
        <span class="email">{{ $person->email }}</span>
        <span class="tell">{{ $person->tel }}</span>

        {{ link_to_route('admin.choir.director.edit', 'Edit', [$choir, $person], ['class' => 'action pull-right'])}}
      </li>
    @endforeach
  </ul>
@endif

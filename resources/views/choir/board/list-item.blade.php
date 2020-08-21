<li class="choir card list-group-item" data-resource-type="choir" data-resource-id="{{ $choir->id }}">

  @if($choir->school)
    <span class="school">{{ $choir->school->name }}</span>
  @endif

  <span class="name">{{ $choir->name }}</span>

  @foreach($choir->directors as $director)
    <span class="location">{{$director->fullName}}</span>
    <div class="d-flex" style="justify-content: space-between">
      <span>Email</span>
      <span>
        @php
          $email = $director->email;
          $secure_email = substr($email, 0, 2);
          $secure_email .= '****@****' . substr($email, -7);
          echo $secure_email;
        @endphp
      </span>
    </div>
    @if($director->tel)
      <div class="d-flex" style="justify-content: space-between">
        <span>Phone</span>
        <span>
          @php
            echo '( *** ) *** - ' . explode('-', $director->tel)[1];
          @endphp
        </span>
      </div>
    @endif
  @endforeach

  <div class="actions">
    @can('removeChoir', $division)
      <a class="remove-resource" data-resource-type="choir" data-resource-id="{{ $choir->id }}" data-csrf-token="{{ csrf_token() }}" href="{{ route('organizer.competition.division.choir.destroy',[$division->competition,$division,$choir]) }}">Remove</a>
    @endcan
  </div>
</li>

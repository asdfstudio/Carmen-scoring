<li class="choir card list-group-item" data-resource-type="choir" data-resource-id="{{ $choir->id }}">

  <div class="dg-card-dec1"></div>

  <span class="name text-center">{{ $choir->name }}</span>

  @if($choir->school)
    <span class="school text-center">{{ $choir->school->name }}</span>
  @endif

  @foreach($choir->directors as $director)
    <div class="director-div">
      <i class="fa fa-user dg-fs-20"></i>
      <span class="dg-fs-18">{{$director->fullName}}</span>
    </div>
    <div class="d-flex email-div" style="justify-content: space-between">
      <span><i class="fa fa-envelope dg-fs-16"></i>Email</span>
      <span class="location">
        @php
          $email = $director->email;
          $secure_email = substr($email, 0, 2);
          $secure_email .= '****@****' . substr($email, -7);
          echo $secure_email;
        @endphp
      </span>
    </div>
    @if($director->tel)
      <div class="d-flex phone-div" style="justify-content: space-between">
        <span><i class="fa fa-phone dg-fs-20"></i>Phone</span>
        <span class="location">
          @php
            echo '( *** ) *** - ' . explode('-', $director->tel)[1];
          @endphp
        </span>
      </div>
    @endif
  @endforeach

  <div class="actions text-center">
    @can('removeChoir', $division)
      <a class="remove-resource" data-resource-type="choir" data-resource-id="{{ $choir->id }}" data-csrf-token="{{ csrf_token() }}" href="{{ route('organizer.competition.division.choir.destroy',[$division->competition,$division,$choir]) }}">
        <i class="fa fa-trash"></i>
        Remove
      </a>
    @endcan
  </div>
</li>

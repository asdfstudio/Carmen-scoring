<li class="card-prototype card choir list-group-item" data-resource-type="choir" data-resource-id="@{{id}}">

  <div class="dg-card-dec1"></div>
  <span class="name text-center">@{{name}}</span>
  <span class="school text-center">@{{school.name}}</span>

  @{{ #directors}}
    <div class="director-div">
      <i class="fa fa-user ss-fs-20"></i>
      <span class="ss-fs-18">@{{fullName}}</span>
    </div>
    <div class="d-flex email-div" style="justify-content: space-between">
      <span><i class="fa fa-envelope ss-fs-16"></i>Email</span>
      <span class="location">
        @{{ email }}
      </span>
    </div>
    @{{ #tel }}
      <div class="d-flex phone-div" style="justify-content: space-between">
        <span><i class="fa fa-phone ss-fs-20"></i>Phone</span>
        <span class="location">
          @{{ tel }}
        </span>
      </div>
    @{{ /tel }}
  @{{ /directors}}

  <div class="actions text-center">
    <a class="remove-resource" data-resource-type="choir" data-resource-id="@{{ id }}" data-csrf-token="{{ csrf_token() }}" href="{{ route('organizer.competition.division.show',[$division->competition,$division]) }}/choir/@{{id}}">
      <i class="fa fa-trash"></i>  
      Remove
    </a>
  </div>
</li>

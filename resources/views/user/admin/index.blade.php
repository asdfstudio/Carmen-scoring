@extends('layouts.simple')

@section('breadcrumbs')

@endsection

@section('content-header')
	<h1>User &amp; Person Records</h1>

	<ul class="actions-group">
		@can('create' , 'App\User')
		  <li>{{ link_to_route('admin.user.create', 'Add a user', [], ['class' => 'action']) }}</li>
		  <li>{{ link_to_route('admin.person.create', 'Add a person', [], ['class' => 'action']) }}</li>
		@endcan
	</ul>

  <div style="clear: both;">

  <div id="user-person-filter-group">
    <a href="#" class="btn btn-primary user-person-filter active" data-filter="">View All</a>
    <a href="#" class="btn btn-primary user-person-filter" data-filter="users-only">Users Only</a>
    <a href="#" class="btn btn-primary user-person-filter" data-filter="non-users-only">Non-Users Only</a>
  </div>
  <form id="user-person-search">
    <img src='data:image/svg+xml;utf8,<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="search" class="svg-inline--fa fa-search fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"></path></svg>'>
    <input type="text" placeholder="Search name, username, or email">
  </form>
  
  <hr>
  
@endsection

@section('content')

  <div id="user-person-list">

    @if($people->isEmpty())
      <p>There are no records to show.</p>
    @endif

    @if(!$people->isEmpty())
      @foreach($people as $i => $person)
        <div id="person-{{ $person->id }}" class="person {{ isset($person->user) ? 'user' : 'non-user' }} {{ $i % 2 !== 0 ? 'even' : '' }}" data-fullname="{{ $person->full_name }}" data-firstname="{{ $person->first_name }}" data-lastname="{{ $person->last_name }}" data-username="{{ isset($person->user) ? $person->user->username : '' }}" data-email="{{ $person->email }}">
          @if($person->user)
            <div class="user-flag">
              User
            </div>
          @else
            <div class="user-flag">
              Non-User
            </div>
          @endif
          <div class="name"><span class="name-part first-name">{{ $person->first_name }}</span> <span class="name-part last-name">{{ $person->last_name }}</span></div>
          <div class="user-blocks">
            <div class="user-details">
              @if($person->user)
              <div class="username detail">
                <span class="detail-label">Username:</span>
                <span class="detail-value">{{ $person->user->username }}</span>
              </div>
              @endif
              <div class="email detail">
                <span class="detail-label">Email:</span>
                <span class="detail-value">{{ $person->email }}</span>
              </div>
              @if($person->emails_additional)
                <div class="emails-additional detail">
                  <span class="detail-label">Additional Emails:</span>
                  <span class="detail-value">{{ $person->emails_additional }}</span>
                </div>
              @endif
              @if($person->tel)
                <div class="emails-additional detail">
                  <span class="detail-label">Phone:</span>
                  <span class="detail-value">{{ $person->tel }}</span>
                </div>
              @endif
              @if(!empty($type_names = $person->typeNames()) || ($person->user && $person->user->is_admin))
                <div class="roles detail">
                  <span class="detail-label">Roles:</span>
                  <span class="detail-value">
                    @if($person->user && $person->user->is_admin)
                      <span class="role user-role">Carmen Admin</span>
                    @endif
                    @foreach($type_names as $type_name)
                      <span class="role person-role">{{ $type_name }}</span>
                    @endforeach
                  </span>
                </div>
              @endif
              @if(!empty($choirs = $person->choirs()))
                <div class="choirs detail">
                  <span class="detail-label">Choirs:</span>
                  <span class="detail-value">
                    @foreach($choirs as $j => $choir)
                      <a href="{{ route('admin.choir.show', [$choir]) }}">{{ $choir->name }}</a>{{ $j < count($choirs)-1 ? ', ' : '' }}
                    @endforeach
                  </span>
                </div>
              @endif
              @if(!empty($schools = $person->schools()))
                <div class="schools detail">
                  <span class="detail-label">Schools:</span>
                  <span class="detail-value">
                    @foreach($schools as $j => $school)
                      <a href="{{ route('admin.school.edit', [$school]) }}">{{ $school->name }}</a>{{ $j < count($schools)-1 ? ', ' : '' }}
                    @endforeach
                  </span>
                </div>
              @endif
              @if($person->user && $person->user->organization_id)
                <div class="org-name detail">
                  <span class="detail-label">Organization:</span>
                  <span class="detail-value"><a href="{{ route('admin.organization.show', [$person->user->organization]) }}">{{ $person->user->organization->name }}</a></span>
                </div>
                @if($person->user->organization_role)
                  <div class="org-role detail">
                    <span class="detail-label">Organizational Role:</span>
                    <span class="detail-value">{{ $person->user->organization_role === 'admin' ? 'Administrator' : '' }}{{ $person->user->organization_role === 'standard' ? 'Standard User' : '' }}</span>
                  </div>
                @endif
              @endif
            </div>
            <div class="user-actions">
              <div class="user-actions-title">Actions:</div>
              @if($person->user)
                @can('update', $person->user)
                  <a href="{{ route('admin.user.edit', [$person->user]) }}" class="btn action">Edit User</a>
                @endcan
                @can('destroy', $person->user)
                  @if(!$person->user->isSuperAdmin())
                    {!! form($deleteUserForm,['url' => route('admin.user.destroy',[$person->user])]) !!}
                  @endif
                @endcan
              @else
                @can('update', $person)
                  <a href="{{ route('admin.person.edit', [$person]) }}" class="btn action">Edit Person</a>
                @endcan
                @can('destroy', $person)
                  {!! form($deletePersonForm,['url' => route('admin.person.destroy',[$person])]) !!}
                @endcan
              @endif
            </div>
          </div>
        </div>
      @endforeach
    @endif

  </div>

@endsection

@section('body-footer')
  <script>
    $(document).ready(function(){
      
      $('.user-person-filter').click(function(e){
        e.preventDefault();
        $('.user-person-filter').removeClass('active');
        $(this).addClass('active');
        var filter = $(this).data('filter');
        $('#user-person-list').removeClass('users-only non-users-only').addClass(filter);
        $('#user-person-list .person').removeClass('even').filter(':visible:odd').addClass('even');
      });
      
      $('#user-person-search input').on('input', function(e){
        var searchString = $(this).val().toLowerCase();
        
        if(searchString.length > 0){
          $('#user-person-list .person').each(function(i){
            var fullname = $(this).data('fullname').toLowerCase();
            var lastname = $(this).data('lastname').toLowerCase();
            var username = $(this).data('username').toLowerCase();
            var email = $(this).data('email').toLowerCase();
            
            if(fullname.indexOf(searchString) === 0 || lastname.indexOf(searchString) === 0 || username.indexOf(searchString) === 0 || email.indexOf(searchString) === 0){
              $(this).removeClass('search-hidden');
            } else {
              $(this).addClass('search-hidden');
            }
          });
        } else {
          $('#user-person-list .person').removeClass('search-hidden');
        }
        
        $('#user-person-list .person').removeClass('even').filter(':visible:odd').addClass('even');;
      });
      
    });
  </script>
@endsection

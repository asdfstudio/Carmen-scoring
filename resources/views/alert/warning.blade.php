@if (Session::has('warning'))
  <div class="alert alert-warning d-flex">
    <i class="fa fa-exclamation-triangle dg-fs-20 mr"></i>
      {{ Session::get('warning') }}
  </div>
@endif

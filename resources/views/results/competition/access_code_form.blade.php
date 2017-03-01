@if(session('access_code_alert'))
  <p class="alert alert-danger">{{ session('access_code_alert') }}</p>
@endif

<div class="alert alert-info">
  <h3>Particants - Access Full Results</h3>
  <p>Enter the access code for this competition.</p>

  {!! form($accessCodeForm) !!}
</div>

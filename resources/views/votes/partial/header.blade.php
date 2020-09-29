<div class="container">
  <div class="row">
    <div class="col-lg-12 col-md-12">
      <nav class="navbar navbar-expand-md">
        <a class="navbar-brand" href="index.html">
          <img src="/assets/images/logo-2020.png">
        </a>
        <div id="user-header" class="justify-content-end ml-auto {{$audience->is_premium_vote?'premium':'free'}}">
          @include('votes.partial.user-header')
        </div>
      </nav>
    </div>
  </div>
</div>

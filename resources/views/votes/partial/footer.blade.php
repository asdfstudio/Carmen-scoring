<footer>
  <div class="footerLast">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 col-md-8 col-12">
          Copy right © Voting 2019. All Right Reserved.
        </div>
        <div class="col-lg-6 col-md-4 col-12">
          <ul class="socilList">
            @if(isset($audience) AND !'' == $audience->social['facebook'])
              <li><a href="{{$audience->social['facebook']}}" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
            @endif
            @if(isset($audience) AND !'' == $audience->social['twitter'])
              <li><a href="{{$audience->social['twitter']}}" target="_blank"><i class="fab fa-twitter"></i></a></li>
            @endif
            @if(isset($audience) AND !'' == $audience->social['instagram'])
              <li><a href="{{$audience->social['instagram']}}" target="_blank"><i class="fab fa-instagram"></i></i></a></li>
            @endif
          </ul>
        </div>
      </div>
    </div>
  </div>
</footer>

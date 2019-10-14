<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@section('title') Carmen Scoring @show</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" >
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Bitter:400,700|Lato:100,300,400,700">

    <link rel="stylesheet" href="/css/carmen.css">
    <link rel="stylesheet" href="/css/unslider.css">
    <link rel="stylesheet" href="/css/unslider-dots.css">

</head>
<body id="public-layout">
  <nav>

    @php $active_page = Request::segment(1); @endphp

    <ul>
      <li>

        <a href="/" class="logo @php if($active_page == '') echo 'active'; @endphp"><img src="/images/flower.png" alt="Carmen Scoring System" width="24px"></a>
      </li>
      <!--<li>

        <a href="/" class="@php if($active_page == '') echo 'active'; @endphp">Home</a>
      </li>-->
      <li>
        <a href="/system" class="@php if($active_page == 'system') echo 'active'; @endphp">Information</a>
      </li>
      <li>
        <a href="/about" class="@php if($active_page == 'about') echo 'active'; @endphp">About</a>
      </li>
      <li>
        <a href="/contact" class="@php if($active_page == 'contact') echo 'active'; @endphp">Contact</a>
      </li>
      <li>
        <a href="/contest" class="@php if($active_page == 'contest') echo 'active'; @endphp">Contest</a>
      </li>
      <li>
        <a href="{{ route('results.index') }}" class="@php if($active_page == 'results') echo 'active'; @endphp">Results</a>
      </li>


      @if(Auth::guest())
        <li>
          <a href="/login" class="@php if($active_page == 'login') echo 'active'; @endphp">Login</a>
        </li>
      @endif

      @if(Auth::check())

        @php
        if(Auth::user()->isAdmin())
        {
          $route = 'admin.dashboard';
        }
        elseif(Auth::user()->isOrganizer())
        {
          $route = 'organizer.competition.index';
        }
        elseif(Auth::user()->isJudge())
        {
          $route = 'judge.competition.index';
        }
        else {
          $route = false;
        }
        @endphp

        @if($route)
          <li>
            <a href="{{ route($route) }}">Enter Carmen App</a>
          </li>
        @endif

      @endif

    </ul>
  </nav>
  <div class="">
    @yield('body-content')
  </div>

  <script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
	<script src="/js/unslider-min.js"></script>
  <script>
		jQuery(document).ready(function($) {
			$('.slider').unslider({
        arrows: false,
        autoplay: true
      });
		});
	</script>
</body>
</html>

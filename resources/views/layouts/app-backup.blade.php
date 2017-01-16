<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@section('title') Carmen Scoring @show</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Bitter:400,700|Lato:100,300,400,700">

    <!-- Styles -->
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.3/css/selectize.bootstrap3.min.css">

    <link rel="stylesheet" href="/css/carmen.css">

    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

    <style>

        .fa-btn {
            margin-right: 6px;
        }
    </style>
</head>
<body id="app-layout">
    @section('navbar')
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container-fluid">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    Carmen Scoring
                </a>
            </div>


            @if (Auth::guest())
                @include('navigation.public.main')
            @else

            	@if (Auth::user()->isAdmin())
            		@include('navigation.admin.main')
              @endif

              @if (Auth::user()->isOrganizer())
            		@include('navigation.organizer.main')
              @endif

              @if (Auth::user()->isJudge())
            		@include('navigation.judge.main')
              @endif

            @endif

        </div>
    </nav>
    @show


    @yield('content')

    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.3/js/standalone/selectize.min.js"></script>
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}

    <script>
        $(document).ready(function() {
            $('.add-to-collection').on('click', function(e) {
                e.preventDefault();
                var container = $('.collection-container');
                var count = container.children().length;
                var proto = container.data('prototype').replace(/__NAME__/g, count);
                container.append(proto);

                var choir_container = container.find('.choir_container:last');
                choir_container.find('.new_choir_container').addClass('hidden');
                choir_container.find('.new_school_container').addClass('hidden');
            });

            $('.toggle-new-choir-container').on('click', function(e) {
              e.preventDefault();
              var parent = $(this).parents('.choir_container');
              parent.find('.new_choir_container').removeClass('hidden');
              parent.find('.new_school_container').addClass('hidden');
              parent.find('.existing_choir_container').addClass('hidden');
              $(this).addClass('hidden');
            });

            $('.toggle-new-school-container').on('click', function(e) {
              e.preventDefault();
              var parent = $(this).parents('.choir_container');
              parent.find('.new_school_container').removeClass('hidden');
              parent.find('.existing_school_container').addClass('hidden');
              $(this).addClass('hidden');
            });

            $('.toggle-new-judge-container').on('click', function(e) {
              e.preventDefault();
              var parent = $(this).parents('.judge_container');
              parent.find('.new_judge_container').removeClass('hidden');
              parent.find('.existing_judge_container').addClass('hidden');
              $(this).addClass('hidden');
            });
        });
    </script>

    <script>
      $(document).ready(function() {

        $('.new_choir_container').addClass('hidden');
        $('.new_school_container').addClass('hidden');
        $('.new_judge_container').addClass('hidden');

    		$('.choir_id').selectize({
    			//persist: false,
    			//createOnBlur: true,
    			create: true
    		});


      // scorecard
      $('ul.number-selector a').on('click', function(e) {
        e.preventDefault();

        var criterion_id = $(this).data('criterion-id');
        var number = $(this).data('number');

        // Update the input value
        $('.score input[data-criterion-id="'+criterion_id+'"]').val(number);

        // Highlight the current selection
        $(this).parents('ul.number-selector').find('li a').removeClass('current');
        $(this).addClass('current');

        console.log(criterion_id + ':' + number);
      });


      });
  	</script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{csrf_token()}}" />

    <title>@section('title') Carmen Scoring System @show</title>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" href="images/fevicon.png" />

    <link rel="stylesheet" type="text/css" href="/front/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="/front/css/owl.carousel.min.css" />
    <link rel="stylesheet" type="text/css" href="/front/css/owl.theme.default.min.css" />
    <link rel="stylesheet" type="text/css" href="/front/css/animate.css" />
    <link rel="stylesheet" type="text/css" href="/front/css/pull-push.css" />
    <link rel="stylesheet" type="text/css" href="/front/css/Menustyle.css" />
    <link rel="stylesheet" type="text/css" href="/front/css/style.css" />
    <link rel="stylesheet" type="text/css" href="/front/css/responsive.css" />
    <link rel="stylesheet" type="text/css" href="/front/css/fontawesome-all.css">
    <link rel="stylesheet" type="text/css" href="/front/css/circular-std.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/regular.css"
          integrity="sha384-zkhEzh7td0PG30vxQk1D9liRKeizzot4eqkJ8gB3/I+mZ1rjgQk+BSt2F6rT2c+I" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/fontawesome.css"
          integrity="sha384-HbmWTHay9psM8qyzEKPc8odH4DsOuzdejtnr+OFtDmOcIVnhgReQ4GZBH7uwcjf6" crossorigin="anonymous">


    @yield('style')

</head>
<body id="app-layout">
<header>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">

                <nav class="navbar navbar-expand-md">
                    <a class="navbar-brand" href="index.html">
                        <img src="/front/images/logo.png">
                    </a>

                    <div id="myDIV" class="login justify-content-end ml-auto">
                        <button class="btn active" data-toggle="modal"
                                data-target="#logInSignUpModal"><i class="fas fa-user"></i> Login</button>

                        <button class="btn " data-toggle="modal"
                                data-target="#SignUpModal">Sign up</button>

                    </div>

                </nav>
            </div>
        </div>
    </div>
</header>

    @yield('content')

    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.3/js/standalone/selectize.min.js"></script>
    <script src="/js/mic-recorder.js"></script>
    <script src="/js/jquery-ui/jquery-ui.min.js"></script>
    <script src="/front/js/modernizr.js"></script>


    <script src="/front/js/fontawesome.js"></script>
    <script src="/front/js/owl.carousel.js"></script>
    <script src="/front/js/wow.min.js"></script>
    <script src="/front/js/popper.min.js"></script>
    <script src="/front/js/sticky_script.js"></script>
    <script src="/front/js/bootstrap.min.js"></script>

    <script src="/front/js/jquery.menu-aim.js"></script>
    <script src="/front/js/Menumain.js"></script>
    @yield('body-footer')

</body>
</html>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Carmen-Scoring system</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <link rel="shortcut icon" href="/assets/images/fevicon.png"/>
  <link rel="stylesheet" type="text/css" href="/assets/css/card-js.min.css">
  <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css"/>
  <link rel="stylesheet" type="text/css" href="/assets/css/animate.css"/>
  <link rel="stylesheet" type="text/css" href="/assets/css/circular-std.css">
  <link rel="stylesheet" type="text/css" href="/assets/css/fontawesome-all.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/regular.css" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/fontawesome.css"crossorigin="anonymous">
  @yield('style')
</head>
<body @yield('extra_class')>
<div class="votepage-content">
  @yield('content')
</div>

@include('votes.partial.footer')
@include('votes.partial.modal')

<script src="/assets/js/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="/assets/js/fontawesome.js"></script>
<script src="/assets/js/bootstrap.min.js"></script>
<script src="/assets/js/card-js.min.js"></script>
<script src="/assets/js/vote-script.js"></script>

<script>
  // Add active class to the current button (highlight it)
  var header = document.getElementById("myDIV");
  var btns = header.getElementsByClassName("btn");
  for (var i = 0; i < btns.length; i++) {
    btns[i].addEventListener("click", function () {
      var current = document.getElementsByClassName("active");
      current[0].className = current[0].className.replace(" active", "");
      this.className += " active";
    });
  }
  @if(isset($_REQUEST['verify']) && 'verified' === $_REQUEST['verify'])
    Swal.fire({
      title: 'Success!',
      html: '<p class="h5 py-4">Thanks for verifying your account! <br/>You can start vote after login</p>',
      icon: 'success',
      showConfirmButton: false,
    }).then(() => {
      window.location.href = '{!! url()->current() !!}'
    })
  @endif
</script>
</body>

</html>

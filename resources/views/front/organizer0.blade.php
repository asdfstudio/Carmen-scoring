@extends('layouts.front_light')
@section('content')
  <section class="heroVideo ptb_80">
    <div class="container">
      <div class="row">
        <div class="embed-responsive embed-responsive-16by9">
          <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/zpOULjyy-n8?rel=0" allowfullscreen></iframe>
        </div>
        <div class="videoTitle">
          <h2 class="videoTitle">Preliminary Round – Week 4
            <span>Choose the soloist that you would like to see in the next round.</span>
          </h2>
        </div>
      </div>
    </div>
  </section>

  <section class="userSection ptb_80">
    <div class="container">
      <div class="row">
        <div class="col-lg-4">
          <div class="white-bg wbg2 Red">

            <div class="userInfo">
              <h3>Alexa Sekercak
                <span>Male, 22</span>
              </h3>
              <button type="button" class="btn like"><i class="fas fa-thumbs-up"></i> 1,222</button>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="white-bg wbg2 Green">

            <div class="userInfo">
              <h3>Alexa Sekercak
                <span>Male, 22</span>
              </h3>
              <button type="button" class="btn like"><i class="fas fa-thumbs-up"></i> 1,222</button>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="white-bg wbg2 Orange">

            <div class="userInfo">
              <h3>Alexa Sekercak
                <span>Male, 22</span>
              </h3>
              <button type="button" class="btn like"><i class="fas fa-thumbs-up"></i> 1,222</button>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="white-bg wbg2 Purple">

            <div class="userInfo">
              <h3>Alexa Sekercak
                <span>Male, 22</span>
              </h3>
              <button type="button" class="btn like"><i class="fas fa-thumbs-up"></i> 1,222</button>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="white-bg wbg2 Blue">

            <div class="userInfo">
              <h3>Alexa Sekercak
                <span>Male, 22</span>
              </h3>
              <button type="button" class="btn like"><i class="fas fa-thumbs-up"></i> 1,222</button>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="white-bg wbg2 Black">

            <div class="userInfo">
              <h3>Alexa Sekercak
                <span>Male, 22</span>
              </h3>
              <button type="button" class="btn like"><i class="fas fa-thumbs-up"></i> 1,222</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <footer>
    <div class="footerLast">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 col-md-8 col-12">
            Copy right © Voting 2019. All Right Reserved.
          </div>
          <div class="col-lg-6 col-md-4 col-12">
            <ul class="socilList">
              <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
              <li><a href="#"><i class="fab fa-twitter"></i></a></li>
              <li><a href="#"><i class="fab fa-instagram"></i></a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </footer>

  <!-- The Modal -->
  <div class="modal fade loginModal" id="logInSignUpModal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <!-- Modal body -->
        <div class="modal-body">
          <div class="loginMainContent">
            <button type="button" class="close mobileShow" data-dismiss="modal"></button>
            <!-- Nav pills -->

            <!-- Tab panes -->
            <div class="tab-content">
              <div class="tab-pane active" id="LOGIN_Pill">
                <div class="formCol_">
                  <form>
                    <div class="form-group">

                      <input type="text" name="" class="lineTag is-invalid"
                             placeholder="Enter mobile no / email id">
                      <label class="f_label">Mobile Number (10-digit) / Email Address</label>
                      <div class="invalid-feedback">
                        Please enter email or mobile number
                      </div>
                    </div>
                    <div class="form-group">
                      <input type="text" name="" class="lineTag is-invalid"
                             placeholder="Enter password">
                      <label class="f_label">Enter Your Password</label>
                      <div class="invalid-feedback">
                        Please enter correct password
                      </div>
                    </div>
                    <div>
                      <button type="button" class="btn_modalFull" id="logInOTP_Show">Login Using
                        OTP</button>
                    </div>
                  </form>
                </div>
              </div>

            </div>
            <div class="Login_Using_OTP OTP_content">
              <div class="row OTP_Head">
                <div class="col-md-3 col-3">
                  <button type="button" class="backArrowBtn" id="logInOTP_Hide">
                    <i class="loginAdIcon loginAdIcon_backNav"></i>
                  </button>
                </div>
                <div class="col-md-9 col-9">
                  <h4 class="t_h4">Login Using OTP</h4>
                </div>
              </div>
              <div class="OTPBody">
                <form class="row m-0">
                  <div class="col-12">
                    <p class="not_">Please check the OTP sent to your mobile number</p>
                  </div>
                  <div class="col-12 flexColCenter">
                    <div class="mobileNo_Email">demo@gmail.com</div>
                    <div class="changeFieldCol">
                      <button type="button" class="btn_Chang">Change</button>
                    </div>
                  </div>
                  <div class="col-12 enterOTPCol">
                    <label>Enter OTP</label>
                    <input type="text" name="" ng-minlength="6" maxlength="6" allow-only-numbers>
                  </div>
                  <div class="col-12">
                    <button type="button" class="resendOTP_BTN">Resend OTP</button>
                  </div>
                  <div class="col-12 textContent">
                    <div id="otpTime"></div>
                  </div>
                </form>
              </div>
              <button type="button" class="btn_modalFull">Login</button>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <div class="modal fade loginModal" id="SignUpModal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <!-- Modal body -->
        <div class="modal-body">
          <div class="loginMainContent">
            <button type="button" class="close mobileShow" data-dismiss="modal"></button>
            <!-- Nav pills -->

            <!-- Tab panes -->
            <div class="tab-content">
              <div class="tab-pane active" id="LOGIN_Pill">
                <div class="formCol_">
                  <form>
                    <div class="form-group">

                      <input type="text" name="" class="lineTag is-invalid"
                             placeholder="Enter mobile no / email id">
                      <label class="f_label">Mobile Number (10-digit) / Email Address</label>
                      <div class="invalid-feedback">
                        Please enter email or mobile number
                      </div>
                    </div>
                    <div class="form-group">
                      <input type="text" name="" class="lineTag is-invalid"
                             placeholder="Enter password">
                      <label class="f_label">Enter Your Password</label>
                      <div class="invalid-feedback">
                        Please enter correct password
                      </div>
                    </div>
                    <div class="form-group">
                      <input type="text" name="" class="lineTag is-invalid"
                             placeholder="Enter correct password">
                      <label class="f_label">Enter Correct Password</label>
                      <div class="invalid-feedback">
                        Please enter correct password
                      </div>
                    </div>
                    <div>
                      <button type="button" class="btn_modalFull" id="logInOTP_Show">Login Using
                        OTP</button>
                    </div>
                  </form>
                </div>
              </div>

            </div>
            <div class="Login_Using_OTP OTP_content">
              <div class="row OTP_Head">
                <div class="col-md-3 col-3">
                  <button type="button" class="backArrowBtn" id="logInOTP_Hide">
                    <i class="loginAdIcon loginAdIcon_backNav"></i>
                  </button>
                </div>
                <div class="col-md-9 col-9">
                  <h4 class="t_h4">Login Using OTP</h4>
                </div>
              </div>
              <div class="OTPBody">
                <form class="row m-0">
                  <div class="col-12">
                    <p class="not_">Please check the OTP sent to your mobile number</p>
                  </div>
                  <div class="col-12 flexColCenter">
                    <div class="mobileNo_Email">demo@gmail.com</div>
                    <div class="changeFieldCol">
                      <button type="button" class="btn_Chang">Change</button>
                    </div>
                  </div>
                  <div class="col-12 enterOTPCol">
                    <label>Enter OTP</label>
                    <input type="text" name="" ng-minlength="6" maxlength="6" allow-only-numbers>
                  </div>
                  <div class="col-12">
                    <button type="button" class="resendOTP_BTN">Resend OTP</button>
                  </div>
                  <div class="col-12 textContent">
                    <div id="otpTime"></div>
                  </div>
                </form>
              </div>
              <button type="button" class="btn_modalFull">Login</button>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
@endsection
@section('body-footer')
   <script>
     //Testimonial Slider
     $('.catThumbb').owlCarousel({
       loop: true,
       margin: 10,
       responsiveClass: true,
       responsive: {
         0: {
           items: 1,
           nav: true
         },
         600: {
           items: 3,
           nav: false
         },
         1000: {
           items: 5,
           nav: true,
           loop: false
         }
       }
     });


     new WOW().init();

     //Cat MegaMenu

     $(document).on('click', '.dropdown-menu', function (e) {
       e.stopPropagation();
     });

     // clickable on mobile view
     if ($(window).width() < 992) {
       $('.has-submenu a').click(function (e) {
         e.preventDefault();
         $(this).next('.megasubmenu').toggle();

         $('.dropdown').on('hide.bs.dropdown', function () {
           $(this).find('.megasubmenu').hide();
         })
       });
     }


     // Tabs
     $('.continue').click(function () {
       $('.nav-tabs > .active').next('li').find('a').trigger('click');
     });
     $('.back').click(function () {
       $('.nav-tabs > .active').prev('li').find('a').trigger('click');
     });


     //Testimonial Slider
     $('.owl-carousel').owlCarousel({
       loop: true,
       margin: 10,
       responsiveClass: true,
       responsive: {
         0: {
           items: 1,
           nav: true
         },
         600: {
           items: 1,
           nav: false
         },
         1000: {
           items: 1,
           nav: true,
           loop: true
         }
       }
     });



     //Add to cart animation
     $(document).ready(function () {
       $('.addtocart').on('click', function () {

         var button = $(this);
         var cart = $('.cart');
         var cartTotal = cart.attr('data-totalitems');
         var newCartTotal = parseInt(cartTotal) + 1;

         button.addClass('sendtocart');
         setTimeout(function () {
           button.removeClass('sendtocart');
           cart.addClass('shake').attr('data-totalitems', newCartTotal);
           setTimeout(function () {
             cart.removeClass('shake');
           }, 500)
         }, 1000)
       })
     })
   </script>


   <script>
     $(document).ready(function () {
       $("#logInOTP_Hide").click(function () {
         $(".Login_Using_OTP").hide();
       });
       $("#logInOTP_Show").click(function () {
         $(".Login_Using_OTP").show();
       });
       $("#signUpOTP_Hide").click(function () {
         $(".Signup_Using_OTP").hide();
       });
       $("#signUpOTP_Show").click(function () {
         $(".Signup_Using_OTP").show();
       });
     });
   </script>

   <script>
     time = 30, otpTime = document.getElementById('otpTime'), tmp = time;
     setInterval(function () {
       var c = tmp--,
               m = (c / 60) >> 0,
               s = (c - m * 60) + '';
       otpTime.textContent = '' + m + ':' + (s.length > 1 ? '' : '0') + s
       tmp != 0 || (tmp = time);
     }, 1000);
   </script>
   <script>
     time = 30, otpTime_2 = document.getElementById('otpTime_2'), tmp = time;
     setInterval(function () {
       var c = tmp--,
               m = (c / 60) >> 0,
               s = (c - m * 60) + '';
       otpTime_2.textContent = '' + m + ':' + (s.length > 1 ? '' : '0') + s
       tmp != 0 || (tmp = time);
     }, 1000);
   </script>

   <script>
     // Add active class to the current button (highlight it)
     var header = document.getElementById("myDIV");
     var btns = header.getElementsByClassName("btn");
     for (var i = 0; i < btns.length; i++) {
       btns[i].addEventListener("click", function() {
         var current = document.getElementsByClassName("active");
         current[0].className = current[0].className.replace(" active", "");
         this.className += " active";
       });
     }
   </script>
@endsection

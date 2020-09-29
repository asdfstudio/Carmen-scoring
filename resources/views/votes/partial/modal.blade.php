<!-- The Modal -->
<div class="modal fade loginModal" id="loginModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <!-- Modal body -->
      <div class="modal-body">
        <div class="loginMainContent">
          <button type="button" class="close mobileShow" data-dismiss="modal"></button>
          <!-- Nav pills -->
          <div class="row OTP_Head">
            <div class="col-md-12">
              <h4 class="t_h4">Login Your Account</h4>
            </div>
          </div>
          <!-- Tab panes -->
          <div class="tab-content">
            <div class="tab-pane active" id="LoginDlg">
              <div class="formCol_">
                <form role="form" method="POST" action="{{ url('/customer-login') }}">
                  @csrf
                  <div class="form-group">
                    <input type="text" name="username" class="lineTag"
                           placeholder="Enter mobile no / email id">
                    <label class="f_label">Email Address</label>
                    <div class="invalid-feedback">
                      Please enter email or mobile number
                    </div>
                  </div>
                  <div class="form-group">
                    <input type="password" name="password" class="lineTag"
                           placeholder="Enter password">
                    <label class="f_label">Enter Your Password</label>
                    <div class="invalid-feedback">
                      Please enter correct password
                    </div>
                  </div>
                  <div class="form-group">
                    <a class="forgotPass" data-toggle="modal" data-target="#ForGotpassword">Forgot Password?</a>
                  </div>
                  <div>
                    <button type="submit" class="btn_modalFull" id="loginBtn">Login</button>
                  </div>
                </form>
              </div>
            </div>

          </div>

        </div>

      </div>
    </div>
  </div>
</div>

<div class="modal fade loginModal" id="SignupModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <!-- Modal body -->
      <div class="modal-body">
        <div class="loginMainContent">
          <button type="button" class="close mobileShow" data-dismiss="modal"></button>
          <!-- Nav pills -->
          <div class="row OTP_Head">
            <div class="col-md-12">
              <h4 class="t_h4">Signup Account</h4>
            </div>
          </div>
          <!-- Tab panes -->
          <div class="tab-content">
            <div class="tab-pane active">
              <div class="formCol_">
                <form id="register-form">
                  @csrf
                  <div class="form-group">
                    <input type="hidden" name="_redirect" value="{!! url()->current() !!}">
                    <input type="email" name="reg_email" class="lineTag"
                           placeholder="Enter mobile no / email id">
                    <label class="f_label">Email Address</label>
                    <div class="invalid-feedback">
                      Please enter your email
                    </div>
                  </div>
                  <div class="form-group">

                    <input type="text" name="reg_name" class="lineTag"
                           placeholder="Enter your name">
                    <label class="f_label">Your username</label>
                    <div class="invalid-feedback">
                      Please enter your username
                    </div>
                  </div>
                  <div class="form-group">
                    <input type="password" name="reg_password" class="lineTag"
                           placeholder="Enter password">
                    <label class="f_label">Enter Your Password</label>
                    <div class="invalid-feedback">
                      Please enter your password
                    </div>
                  </div>
                  <div class="form-group">
                    <input type="password" name="password_confirmation" class="lineTag"
                           placeholder="Enter correct password">
                    <label class="f_label">Enter Confirm Password</label>
                    <div class="invalid-feedback">
                      Please enter confirm password
                    </div>
                  </div>
                  <input type="hidden" name="redirect" value="{{Request::url()}}">
                  <div>
                    <button type="button" class="btn_modalFull" id="signupBtn">Signup</button>
                  </div>
                </form>
                <div class="screen-loading text-center pt-2 d-none">
                  <div class="fa-3x">
                    <i class="fas fa-spinner fa-pulse"></i>
                  </div>
                </div>
                <div class="register-success text-center d-none">
                  <label for="" class="h5 text-danger mb-5">Thanks for your registration!<br>Please verify your account first.</label>
                  <button type="button" class="btn btn-success w-50" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>

          </div>

        </div>

      </div>
    </div>
  </div>
</div>

<div class="modal fade loginModal" id="ForGotpassword">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <!-- Modal body -->
      <div class="modal-body">
        <div class="loginMainContent">
          <button type="button" class="close mobileShow" data-dismiss="modal"></button>
          <!-- Nav pills -->
          <div class="row OTP_Head">
            <div class="col-md-12">
              <h4 class="t_h4">Forgot your password?</h4>
            </div>
          </div>
          <!-- Tab panes -->
          <div class="tab-content">
            <div class="tab-pane active">
              <div class="formCol_">
                <form id="resetpassword-form" action="/password/reset" method="POST">
                  @csrf
                  <div class="form-group row">
                    <label class="col-12" for="email">E-Mail Address</label>

                    <div class="col-12">
                      <input id="email_recover"
                             type="email"
                             class="form-control "
                             name="email_recover"
                             value=""
                             required=""
                             autocomplete="email"
                             autofocus="">
                    </div>
                  </div>

                  <input type="hidden" name="redirect" value="{{Request::url()}}">
                  <div>
                    <button type="submit" class="btn_modalFull" id="resetPassWord">Signup</button>
                  </div>
                </form>
                <div class="screen-loading text-center pt-2 d-none">
                  <div class="fa-3x">
                    <i class="fas fa-spinner fa-pulse"></i>
                  </div>
                </div>
                <div class="recover-success text-center d-none">
                  <label for="" class="h5 text-danger mb-5">Please check your email!<br>An E-mail has been sent.</label>
                  <button type="button" class="btn btn-success w-50" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>

          </div>

        </div>

      </div>
    </div>
  </div>
</div>

<div class="modal fade buypetlpointmodal" id="payment-modal">
  <div class="modal-dialog modal-dialog-centered">
    <button type="button" class="close mobileShow" data-dismiss="modal"></button>
    <div class="modal-content">
      <!-- Modal body -->
      <div class="modal-body">
        <div class="">
          <h2>Buy Petl Points</h2>
          <p>How many points are you going to buy?</p>
        </div>
        <form id="petlPointForm" role="form" method="POST" action="{{ url('/buy-petl-points') }}">
          @csrf

          <div class='form-row'>
            <div class="col pelt-point">
              <label class="sr-only" for="inlineFormInputGroup">Petl Point</label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <div class="input-group-text">$</div>
                </div>
                <input autocomplete='off' type="number" name="petl_point" class="form-control" id="pelt_point" placeholder="1 Petl Point is $1 US">
              </div>
            </div>

          </div>

          <div class="form-row mb-3">
            <div class="col">
              <div class="card-js" id="customer-card" data-capture-name="true"></div>
            </div>
          </div>

          <div class='form-row'>
            <div class='col'>
              <button class='form-control btn btn-secondary' data-dismiss="modal" type='cancel'>Cancel</button>
            </div>
            <div class='col'>
              <button id="buyPetlPoint" class='form-control btn btn-primary submit-button' type='submit'>
                Buy Now
                <i class="fas fa-spinner fa-pulse"></i>
              </button>
            </div>
          </div>

          <div class='form-row mt-3'>
              <div id="alertPayment" class='col-md-12 error form-group'>
                <div class='alert-danger alert'>
                  You not enough Petl Points please make a payment to continue!.
                </div>
              </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

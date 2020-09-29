(function ($) {
  "use strict";
  $(document).ready(function () {
    //Login action
    $("#loginBtn").click(function (e) {
      e.preventDefault();
      const token = $("input[name='_token']").val();
      const email = $("input[name='username']").val();
      const password = $("input[name='password']").val();
      $('.screen-loading').removeClass('d-none');
      $.ajax({
        url: '/user-login',
        type: 'POST',
        data: {
          username: email,
          password: password,
          _token: token
        },
        success: function (data) {
          $('.screen-loading').addClass('d-none');
          $('#user-header').html(data);
          $('.loginModal').modal('hide');
        }
      });

    });

    //Register action
    $('#signupBtn').click(function (e) {
      e.preventDefault();
      const redirect_url = $("input[name='_redirect']").val();
      const token = $("input[name='_token']").val();
      const email = $("input[name='reg_email']").val();
      const username = $("input[name='reg_name']").val();
      const password = $("input[name='reg_password']").val();
      const password_confirm = $("input[name='password_confirmation']").val();
      $('.screen-loading').removeClass('d-none');
      $.ajax({
        url: '/user-register',
        type: 'POST',
        data: {
          'email': email,
          'username': username,
          '_redirect': redirect_url,
          'password': password,
          'password_confirmation': password_confirm,
          '_token': token
        },
        error: function (data) {
          $('.screen-loading').addClass('d-none');
          let message = '';
          $.each(data.responseJSON.errors, function (index, value) {
            message += value+"<br />";
          });

          Swal.fire({
            title: 'Whoop!',
            html: '<p class="h5 py-4">' + message + '</p>',
            icon: 'error',
            showConfirmButton: false,
          })
        },
        success: function (data) {
          if ('Register successfuly' === data.message) {
            $('#register-form').addClass('d-none');
            $('.register-success').removeClass('d-none');
            $('.screen-loading').addClass('d-none');
          }
        }
      });
    });

    //Register action
    $('#resetPassWord').click(function (e) {
      e.preventDefault();
      const token = $("input[name='_token']").val();
      const email = $("input[name='email_recover']").val();
      const redirect = $("input[name='redirect']").val();
      $('#ForGotpassword .screen-loading').removeClass('d-none');

      $.ajax({
        url: '/user-forgot',
        type: 'POST',
        data: {
          'email': email,
          'redirect': redirect,
          '_token': token
        },
        error: function (data) {
          $('#ForGotpassword .screen-loading').addClass('d-none');
          let errors = [];
          $.each(data.responseJSON.errors, function (index, value) {
            errors.push(value);
          });
          Swal.fire({
            title: 'Whoop!',
            html: '<p class="h5 py-4">' + errors + '</p>',
            icon: 'error'
          })
        },
        success: function (data) {
          $('#ForGotpassword .screen-loading').addClass('d-none');
          if ('undefined' === typeof (data.errors)) {
            return Swal.fire({
              title: 'Successfully!',
              html: '<p class="h5 py-4">Please check your email<br> We\'ve sent an email to reset your password!</p>',
              icon: 'success',
            }).then(function () {
              $('#ForGotpassword').modal('hide')
            })
          }
          return Swal.fire({
            title: 'Whoop!',
            html: '<p class="h5 py-4">' + data.errors + '</p>',
            icon: 'error',
          })
        }
      });
    });

    //Forgot password link click
    $('.forgotPass').click(function (e) {
      e.preventDefault();
      $('.loginModal').modal('hide');
    });

    $('.userInfo button').click(function () {
      const voteID = $(this).data('vote');
      const token = $("input[name='_token']").val();
      const audientId = $('input[name="audientId"]').val();
      const wrapper = $(this).closest('.wbg2');
      const likeCount = $(this).find('.vote-count');

      $.ajax({
        url: '/user-vote',
        type: 'POST',
        data: {
          'voteId': voteID,
          'audientId': audientId,
          '_token': token,
        },
        error: function (data) {
          const errorMessage = data.responseJSON.message;

          if('not_enough_petl_points' === errorMessage){
            return $('#payment-modal').modal('show');
          }

          if('need_login' === errorMessage) {
            return $('#loginModal').modal('show');
          }

          return Swal.fire({
            title: errorMessage,
            icon: 'warning'
          });
        },
        success: function (data) {
          likeCount.text(data.vote_count);
          const petlpoints = $('#petlpoints');
          if(petlpoints.length && 'undefined' !== typeof(data.petl_point)){
            petlpoints.text((data.petl_point).toLocaleString('en'));
          }

          if ('Thanks for your voting! If you made a mistake, you can undo your action' === data.message) {
            wrapper.addClass('vote-completely');
          }else {
            wrapper.removeClass('vote-completely');
          }

          Swal.fire({
            title: data.message,
            icon: 'success'
          })
        }
      });
    });

    //Make a payment
    $('#petlPointForm').submit(function(e){
      e.preventDefault();
      const alertZone = $('#alertPayment div');
      const card = $('#customer-card');
      const that = $(this);
      const buttonSubmit = $('#buyPetlPoint');
      const data = {
        _token: $('[name="_token"]').val(),
        petl_point: $('[name="petl_point"]').val(),
        card_no: card.CardJs('cardNumber'),
        ccExpiryMonth: card.CardJs('expiryMonth'),
        ccExpiryYear: card.CardJs('expiryYear'),
        cvvNumber:  card.CardJs('cvc')
      };
      buttonSubmit.addClass('loading');

      $.ajax({
        url: '/buy-petl-points',
        type: 'POST',
        data: data,
        error: function (data) {
          let message = ('undefined' === typeof(data.responseJSON.message))?'':data.responseJSON.message+"<br />";
          $.each(data.responseJSON.errors, function (index, value) {
            message += value+"<br />";
          });
          alertZone.removeClass('alert-success').addClass('alert-danger');
          alertZone.html(message).removeClass('d-none');
          buttonSubmit.removeClass('loading');
        },
        success: function (response) {
          buttonSubmit.removeClass('loading');
          that.trigger('reset');
          $('#petlpoints').text(response.petl_points);
          alertZone.removeClass('alert-danger').addClass('alert-success');
          alertZone.text(response.message).removeClass('d-none');
        }
      });
    });
  });
})(jQuery)

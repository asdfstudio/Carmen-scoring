// Customize the behaviour of the form that creates or edits a user or person.
jQuery(document).ready(function($){
  
  var activeUsernameRequest = false;
  var noUser = $('form.no-user').length;
  var nameFields = $('#first_name, #last_name');
  var firstNameField = $(nameFields).filter('#first_name');
  var lastNameField = $(nameFields).filter('#last_name');
  var usernameField = $('#username');
  var userAccountSection = $('.user-account-section');
  var passwordInputs = $('.password-fields input');
  var passwordLabels = $('.password-fields label');
  var toggleNewUser = $('.toggle-new-user');
  var toggleNewPassword = $('.toggle-new-password');
  var orgSection = $('.org-section');
  var orgId = $('#organization_id');
  
  if(noUser){
    $(nameFields).on('input', updateUsername);
  }
  
  // Make the school selector a fancy Selectized field.
  orgIdSelectize = $(orgId).selectize({
    allowEmptyOption: true,
    placeholder: 'Select an organization...'
  });
  
  if(!orgIdSelectize[0].value){
    // Clear the Selectize field so that the placeholder will show
    // and validation will detect the field as empty.
    orgIdSelectize[0].selectize.clear();
  }
  
  if($(orgId).hasClass('hidden')){
    orgIdSelectize[0].selectize.disable();
  }
  
  $(toggleNewUser).click(function(e){
    e.preventDefault();
    
    if($(userAccountSection).first().hasClass('hidden')){
      $(userAccountSection).removeClass('hidden').find('input, select').prop('disabled', false);
      updateUsername();
      $(orgSection).removeClass('hidden').find('input, select').prop('disabled', false);
      orgIdSelectize[0].selectize.enable();
      $(this).addClass('active');
    } else {
      $(userAccountSection).addClass('hidden').find('input, select').prop('disabled', true).prop('checked', false);
      $(userAccountSection).find('input[type=text], select').val('');
      $(orgSection).addClass('hidden');
      orgIdSelectize[0].selectize.disable();
      orgIdSelectize[0].selectize.clear();
      $(this).removeClass('active');
    }
    
  });
  
  $(toggleNewPassword).click(function(e){
    e.preventDefault();
    
    if($(passwordInputs).first().hasClass('hidden')){
      $(passwordInputs).removeClass('hidden').prop('disabled', false);
      $(passwordLabels).removeClass('hidden');
      $(this).addClass('active');
    } else {
      $(passwordInputs).addClass('hidden').val('').prop('disabled', true);
      $(passwordLabels).addClass('hidden');
      $(this).removeClass('active');
    }
    
  });
  
  function updateUsername(){
    
    if(noUser){
      
      if(activeUsernameRequest){
        activeUsernameRequest.abort();
      }
      
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
      });

      activeUsernameRequest = $.ajax({
        url: getNewUsernameURL,
        method: 'POST',
        data: {
          first_name: $(firstNameField).val(),
          last_name: $(lastNameField).val()
        },
        success: function(result){
          $(usernameField).val(result);
          activeUsernameRequest = false;
        }
      });
      
    }
    
  }
  
});
// Customize the behaviour of the form that creates or edits a user or person.
jQuery(document).ready(function($){
  
  userAccountSection = $('.user-account-section');
  passwordInputs = $('.password-fields input');
  passwordLabels = $('.password-fields label');
  toggleNewUser = $('.toggle-new-user');
  toggleNewPassword = $('.toggle-new-password');
  orgSection = $('.org-section');
  orgId = $('#organization_id');
  
  // Make the school selector a fancy Selectized field.
  orgIdSelectize = $(orgId).selectize({
    allowEmptyOption: true,
    placeholder: 'Select an organization...'
  });

  // Clear the Selectize field so that the placeholder will show
  // and validation will detect the field as empty.
  orgIdSelectize[0].selectize.clear();
  
  if($(orgId).hasClass('hidden')){
    orgIdSelectize[0].selectize.disable();
  }
  
  $(toggleNewUser).click(function(e){
    e.preventDefault();
    
    if($(userAccountSection).first().hasClass('hidden')){
      $(userAccountSection).removeClass('hidden').find('input, select').prop('disabled', false);
      $(orgSection).removeClass('hidden').find('input, select').prop('disabled', false);
      orgIdSelectize[0].selectize.enable();
      $(this).addClass('active');
    } else {
      $(userAccountSection).addClass('hidden').find('input, select').prop('disabled', true).val('').prop('checked', false);
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
      $(passwordInputs).addClass('hidden').prop('disabled', true);
      $(passwordLabels).addClass('hidden');
      $(this).removeClass('active');
    }
    
  });
  
});
var personId, personIdSelectize, schoolId, schoolIdSelectize, toggleNewDirector, directorSearchGroup, initSelectize, activateSearch, activateCreate;

// Customize the behaviour of the forms that add a director to a choir.
jQuery(document).ready(function($){
  
  personId = $('#person-id');
  schoolId = $('#school_id');
  toggleNewDirector = $('.toggle-new-director');
  directorSearchGroup = $('.director-search-group');
  directorCreateGroup = $('.director-create-group');
  
  activateSearch = function(){

    // Make the search field active.
    $(directorSearchGroup).removeClass('disabled');
    $(personId).attr('disabled', '').prop('disabled', false);
    personIdSelectize[0].selectize.enable();

    // Hide, clear, and disable the other fields.
    // Disabling a field negates the "required" property.
    $(directorCreateGroup).hide();
    $(directorCreateGroup).find('input').val('').prop('disabled', true);

  }

  activateCreate = function(){

    // Disable and clear the search field.
    // Disabling a field negates the "required" property.
    $(directorSearchGroup).addClass('disabled');
    $(personId).val('').attr('disabled', 'disabled').prop('disabled', true);
    personIdSelectize[0].selectize.clear();
    personIdSelectize[0].selectize.disable();

    // Show and enable the other fields.
    $(directorCreateGroup).show();
    $(directorCreateGroup).find('input').prop('disabled', false);

  }

  initSelectize = function(){
    // Make the school selector a fancy Selectized field.
    schoolIdSelectize = $(schoolId).selectize({
      allowEmptyOption: true,
      placeholder: 'Select a school...'
    });

    // Make the person selector a fancy Selectized field.
    personIdSelectize = $(personId).selectize({
      allowEmptyOption: true,
      placeholder: 'Select a director...'
    });

    // Clear the Selectize field so that the placeholder will show
    // and validation will detect the field as empty.
    personIdSelectize[0].selectize.clear();
    
    // By default we start with the "search" view.
    activateSearch();
    
    // If the error box is showing, then we need to start with the "create" view.
    if($('.alert-danger').length){
      activateCreate();
    }
    
    // Make sure no click listener is on the toggle link, in case this
    // function has been run more than once.
    $(toggleNewDirector).off('click');
    
    // When the user clicks the "Or create a new director" link...
    $(toggleNewDirector).on('click', function(e){
      e.preventDefault();
      
      if($(directorSearchGroup).hasClass('disabled')){
        activateSearch();
      } else {
        activateCreate();
      }
    });
    
  }
  
  if(!$(personId).is(':hidden')){
    initSelectize();
  }
  
});
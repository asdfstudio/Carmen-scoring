const checkInput = (selector) => {
  let checked = false;
  $(selector).each(function(){
    if($(this).is(':checked')) checked =  true;
    if (checked) return false;
    checked = false;
  });
  return checked;
}

$('.createRound').click(function (){
  const name = $('#name');

  if ('' === name.val() || !checkInput('[name="sheet_id"]') || !checkInput('[name="caption_weighting_id"]') || !checkInput('[name="scoring_method_id"]') ) {
    Swal.fire({
      icon: 'error',
      html: '<h2>Please fill out all the necessary information</h2>',
    });
    return false;
  }
});

/**
 * Check create round with require field
 */
$('.createDivision').click(function (){
  if ('' === $('#name').val() || '' === $('#round_id').val() ) {
    Swal.fire({
      icon: 'error',
      html: '<h2>Please fill out all the necessary information</h2>',
    });
    return false;
  }
});
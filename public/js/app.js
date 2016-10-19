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
      var parent = $(this).parents('form');
      parent.find('.new_choir_container').show();
      parent.find('.new_school_container').hide();
      parent.find('.existing_choir_container').hide();
      $(this).hide();
    });

    $('.toggle-new-school-container').on('click', function(e) {
      e.preventDefault();
      var parent = $(this).parents('form');
      parent.find('.new_school_container').show();
      parent.find('.existing_school_container').hide();
      $(this).hide();
    });

    $('.toggle-new-judge-container').on('click', function(e) {
      e.preventDefault();
      var parent = $(this).parents('form');
      parent.find('.new_judge_container').show();
      parent.find('.existing_judge_container').hide();
      $(this).hide();
    });


    $('.check-all').on('click', function(e) {
      e.preventDefault();
      var checkboxes = $(this).data('checkbox');
      $(document).find("input."+checkboxes).prop('checked', true);
    });

    $('.uncheck-all').on('click', function(e) {
      e.preventDefault();
      var checkboxes = $(this).data('checkbox');
      $(document).find("input."+checkboxes).prop('checked', false);
    });


    //$('.new_choir_container').addClass('hidden');
    //$('.new_school_container').addClass('hidden');
    //$('.new_judge_container').addClass('hidden');

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
    var input = $('.score input[data-criterion-id="'+criterion_id+'"]');
    // Update the input value
    //input.addClass('updating');
    input.val(number);
    //input.removeClass('updating');

    // Highlight the current selection
    $(this).parents('.criterion-container').find('li a').removeClass('current');
    $(this).addClass('current');

    console.log(criterion_id + ':' + number);
    });


    // tabs

    $('.tab-link').on('click', function(e) {
    e.preventDefault();

    // Get the tab ID
    var tab_id = $(this).data('tab-id');

    // Stop if no tab ID
    if(tab_id == false) return false;

    // Hide all tabs
    $('.tab-content[data-tab-id!='+tab_id+']').removeClass('active');

    // Show current tabs
    $('.tab-content[data-tab-id='+tab_id+']').addClass('active');

    // Unhighlight the active tab link
    $(this).parents('.tab-links').find('.tab-link').removeClass('active');

    // Highlight the active tab link
    $(this).addClass('active');

    });


});

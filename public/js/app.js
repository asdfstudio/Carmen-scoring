$(document).ready(function() {


    $('input.ajax-scoring').on('blur', function(){
      var input = $(this);
      var newScore = input.val();
      var originalScore = input.data('original-score');
      var form = input.parents('form');
      var url = form.attr('action');
      var newScore = input.val();
      //console.log(originalScore + ' // ' + newScore);

      if(originalScore == newScore) return false;

      input.addClass('saving');
      input.removeClass('saved');

      data = form.serialize();

      $.post(url, data, function(returnData, status){
        console.log(status);
        //console.log(returnData);
        if(status == 'success')
        {
          input.removeClass('saving');
          input.addClass('saved');
          input.data('original-score', newScore);
        }
        else {
          input.removeClass('saving');
          input.addClass('error');
          alert('There was an error saving your score.');
        }
      });
    });

    $('button.danger, a.danger, submit.danger').on('click', function(e) {
        if(confirm('Are you sure you want to do this?') == false) {
          e.preventDefault();
          console.log('cancel');
        }
    });

    $.fn.toggleChoirSource = function(choir_source) {
      var form = $('form.create-round-form');
      if(choir_source == 'all') {
        form.find('input[name="max_choirs"]').val(0);
        form.find('div.max-choirs-container').hide();
        form.find('div.rounds-container').hide();
      } else {
        //form.find('input[name="max_choirs"]').val('');
        form.find('div.max-choirs-container').show();
        form.find('div.rounds-container').show();
      }
    }

    $('form.create-round-form').ready(function() {
      var choir_source = $(this).find('input[name="choir_source"]:checked').val();
      $(this).toggleChoirSource(choir_source);

    });


    $('form.create-round-form input[name="choir_source"]').on('change', function(e) {
      var choir_source = $(this).val();
      $(this).toggleChoirSource(choir_source);
    });


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



    $.fn.toggleScoreView = function(active_view) {

      if(active_view == false) return false;

      var table = $('table.scoreboard.toggle-scores');
      var scores = table.find('span.score, input.score');
      //var scores = table.find('span.score:not(".penalty")');

      // Hide all scores
      scores.hide();

      // Show the active scores
      scores.filter('.' + active_view).show();

      // Remove highlight from other links
      $('a.score-view-toggle').removeClass('active');

      // Highlight the link that was clicked
      $(this).addClass('active');

    }

    $('table.scoreboard.toggle-scores').ready(function() {
      var active_view = $('.score-view-toggle.active').data('score-view');
      console.log(active_view);
      $('.score-view-toggle.active').toggleScoreView(active_view);
    });


    $('.score-view-toggle').on('click', function(e) {
      e.preventDefault();
      var active_view = $(this).data('score-view');
      $(this).toggleScoreView(active_view);
    });


});

$(document).ready(function() {

    //$(document).on('click', function(e){

      //if($('body').hasClass('input-popup-active') == false) return;

      //console.log($(e.target).parents('score-input-popup'));
      //if(e.target.id == 'score-input-popup' || $(e.target).parent() == 'aaa')
      //console.log(e.target);
      //$('body').removeClass('input-popup-active');
    //});

    /*$('div.popup-input-container').on('blur',function(){
        $(this).removeClass('active');
        $('.toggle-score-input-popup').removeClass('focus');
    });*/

    $('.toggle-score-input-popup').on('focus', function(e){
      e.preventDefault();
      var field = $(this);
      var field_name = field.attr('name');
      var max_score = field.attr('max') * 10;
      var score = field.val() * 10;
      var popup = $('div.popup-input-container');

      $('.toggle-score-input-popup').removeClass('focus');
      field.addClass('focus');
      field.blur();
      popup.focus();

      popup.data('field', field_name);

      // Show the popup
      popup.addClass('active');

      // Set the selected value
      popup.find('a').removeClass('current');
      popup.find('a[data-number="'+score+'"]').addClass('current');

      // Show all the score options
      popup.find('a').show();

      // Then hide those that are greater than max allowed score
      if(max_score < 100) {
        popup.find('a').each(function(index) {
          if($(this).data('number') > max_score) {
            $(this).hide();
          }
        });
      }

    });


    $.fn.autoSave = function() {
      //console.log('attempt autosave');

      var form = $('form.autosave');

      if(form.hasClass('has-changed-data') == false)
      {
        //console.log('No data to save');
        return false;
      }

      var url = form.attr('action');
      var data = form.serialize();

      alertBox = $('div#autosave-alert-box');
      alertBox.removeClass('hide');
      //var alertBoxHTML = '<div id="autosave-alert-box" class="">Autosaving scores...</div>';

      //$('body').append(alertBoxHTML);

      //alertBox.html('Saved!');

      $.post(url, data, function(returnData, status){
        //console.log(status);

        if(status == 'success')
        {
          alertBox.addClass('hide');
          form.removeClass('has-changed-data');
        }
        else {
          alert('There was an error saving your score.');
        }
      });
    }

    $('form.autosave').ready(function() {
      //form = $('form.autosave');
      setInterval(function(){
        $(this).autoSave();
      }, 5000);
    });





    $('input.ajax-scoring').on('blur', function(){
      var input = $(this);
      var newScore = input.val();
      var originalScore = input.data('original-score');
      var form = input.parents('form');
      var url = form.attr('action');
      var newScore = input.val();
      var scoreDifference = newScore - originalScore;

      var scoreWeighting = input.data('score-weighting');
      var newScoreWeighted = newScore * scoreWeighting;
      var weightedScoreDifference = scoreDifference * scoreWeighting;
      //console.log(newScoreWeighted);

      var captionId = input.data('caption-id');
      var choirId = input.data('choir-id');
      var criterionId = input.data('criterion-id');

      // caption score
      var captionScore = $('.caption-total-score[data-caption-id="'+captionId+'"][data-choir-id="'+choirId+'"]');
      var captionOriginalScore = captionScore.data('original-score');
      var newCaptionScore = captionOriginalScore + scoreDifference;

      // total score
      var totalScore = $('.sum-score[data-choir-id="'+choirId+'"]');
      var originalTotalScore = totalScore.data('original-score');

      if(originalTotalScore == false)
        originalTotalScore = 0;

      var newTotalScore = originalTotalScore + scoreDifference;

      //console.log(originalTotalScore + ' // ' + newTotalScore);

      // caption-total-weighted-score
      var captionWeightedScore = $('.caption-total-weighted-score[data-caption-id="'+captionId+'"][data-choir-id="'+choirId+'"]');
      var captionOriginalWeightedScore = captionWeightedScore.data('original-score');
      var newCaptionWeightedScore = captionOriginalWeightedScore + weightedScoreDifference;

      //console.log(newCaptionWeightedScore);

      // total weighted score
      var totalWeightedScore = $('.sum-weighted-score[data-choir-id="'+choirId+'"]');
      var originalTotalWeightedScore = totalWeightedScore.data('original-score');

      if(originalTotalWeightedScore == false)
        originalTotalWeightedScore = 0;

      var newTotalWeightedScore = originalTotalWeightedScore + weightedScoreDifference;




      if(originalScore == newScore) return false;

      input.addClass('saving');
      input.removeClass('saved');

      data = form.serialize();

      //console.log(url);
      //console.log(data);

      //return;

      $.post(url, data, function(returnData, status){
        //console.log(status);
        //console.log(returnData);
        if(status == 'success')
        {
          input.removeClass('saving');
          input.addClass('saved');
          input.data('original-score', newScore);

          // highlight blank scores in spreadsheet
          /*var cell = $('.highlight-zeros[data-choir-id="'+choirId+'"]');

          if (cell) {
            console.log(cell);
            cell.data('raw-score', newScore);
          }*/

          captionScore.html(newCaptionScore);
          captionScore.data('original-score', newCaptionScore);

          captionWeightedScore.html(newCaptionWeightedScore);
          captionWeightedScore.data('original-score', newCaptionWeightedScore);



          totalScore.html(newTotalScore);
          totalScore.data('original-score', newTotalScore);

          totalWeightedScore.html(newTotalWeightedScore);
          totalWeightedScore.data('original-score', newTotalWeightedScore);



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
          //console.log('cancel');
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


    /*$('.toggle-new-choir-container').on('click', function(e) {
      e.preventDefault();
      console.log('toggle choir');
      var parent = $(this).parents('form');
      parent.find('.new_choir_container').show();
      parent.find('.new_school_container').hide();
      parent.find('.existing_choir_container').hide();
      $(this).hide();
    });*/

    /*$('.toggle-new-school-container').on('click', function(e) {
      e.preventDefault();
      var parent = $(this).parents('form');
      parent.find('.new_school_container').show();
      parent.find('.existing_school_container').hide();
      $(this).hide();
    });*/

    $('.toggle-new-judge-container').on('click', function(e) {
      e.preventDefault();
      var parent = $(this).parents('form');
      parent.find('.new_judge_container').show();
      parent.find('.existing_judge_container').hide();
      $(this).hide();
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

    $('body').on('click', '.add-rating button', function(e) {
        e.preventDefault();
        var wrapper = $('.collection-container');
        var container = wrapper.children().first();
        var count = container.children().length;
        var proto = wrapper.data('prototype').replace(/__NAME__/g, count);
        container.append(proto);
    });

    $('body').on('click', '.remove-rating button', function(e) {
        e.preventDefault();
        var ratingGroup = $(this).parent().parent();
        var ratingsContainer = $(ratingGroup).parent();
        var ratingName = ratingGroup.find('input').val();
        var message = '<p class="alert alert-warning">The rating "' + ratingName + '" will be removed when you save this form.</p>';
        ratingGroup.children().remove();
        
        if(ratingName.length){
           ratingGroup.append(message);
        }
        
        if(ratingsContainer.find('.remove-rating').length === 0){
            // If there are no rating fields left, trigger the button to add a new one.
            $('.add-rating button').trigger('click');
        }
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

    // Turned of 2017-11-02 to support new division boards functionality
    /*$('.choir_id').selectize({
      //persist: false,
      //createOnBlur: true,
      create: true
    });*/


    // scorecard comments/feedback
    $('.scorecard textarea[name="comment"]').on('change', function(e) {
      //console.log('comment changed');
      $(this).parents('form').addClass('has-changed-data');
    });

    // scorecard
    $('.scorecard ul.number-selector a').on('click', function(e) {
    e.preventDefault();
    var form = $(this).parents('form');
    form.addClass('has-changed-data');

    var criterion_id = $(this).data('criterion-id');
    var number = $(this).data('number') / 10;
    var input = $('.score input[data-criterion-id="'+criterion_id+'"]');
    // Update the input value
    //input.addClass('updating');
    input.val(number.toFixed(1));
    input.removeClass('missing-score');
    //input.removeClass('updating');

    // Highlight the current selection
    $(this).parents('.criterion-container').find('li a').removeClass('current');
    $(this).addClass('current');

    //console.log(criterion_id + ':' + number);
    });



    // scoreboard popup keyboard
    $('.popup-input-container ul.number-selector a').on('click', function(e) {
      e.preventDefault();

      var popup = $(this).parents('div.popup-input-container');
      var field = popup.data('field');
      var number = $(this).data('number') / 10;
      var input = $('input[name="'+field+'"]');
      var choir_id = input.data('choir-id');
      var criterion_id = input.data('criterion-id');
      var scoreWeighting = input.data('score-weighting');
      var numberWeighted = number * scoreWeighting;

      //console.log(numberWeighted);

      var td = $('td[data-choir-id="'+choir_id+'"][data-criterion-id="'+criterion_id+'"]');

      td.find('span.raw').html(number.toFixed(1));
      td.find('span.weighted').html(numberWeighted.toFixed(1));

      if (number.toFixed(1) <= 0) {
        td.addClass('missing-score');
      } else {
        td.removeClass('missing-score');
      }
      //td.data('raw-score', number.toFixed(1));

      input.val(number.toFixed(1)).trigger('blur');

      popup.find('li a').removeClass('current');

      $(this).addClass('current');

      popup.removeClass('active');
      input.removeClass('focus');

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

      var tables = $('table.scoreboard.toggle-scores');
      var scores = tables.find('span.score, input.score');
      var total_column = tables.find('th.total_column, td.total_column');

      // Hide all scores and tables
      tables.hide();
      scores.hide();
      total_column.hide();

      // Show the active scores and table
      scores.filter('.' + active_view).show();
      tables.filter('.' + active_view).show();
      total_column.filter('.' + active_view).show();

      // Remove highlight from other links
      $('a.score-view-toggle').removeClass('active');

      // Highlight the link that was clicked
      $(this).addClass('active');

    }

    $('table.scoreboard.toggle-scores').ready(function() {
      var active_view = $('.score-view-toggle.active').data('score-view');
      $('.score-view-toggle.active').toggleScoreView(active_view);
    });


    $('.score-view-toggle').on('click', function(e) {
      e.preventDefault();
      var active_view = $(this).data('score-view');
      $(this).toggleScoreView(active_view);
    });



    // Check for missing scores on individual scorecards
    // Give the judge an opportunity to submit as-is or
    // Return to scorecard to fill in missing values
    $('form.scorecard').on('submit', function(e) {

      var score_inputs = $('input.criterion-score-input');
      var score_inputs_count = score_inputs.length;
      var inputs_missing_scores_count = 0;

      score_inputs.each(function(index, element) {
        current_value = $(this).val();
        if(current_value == 0){
          $(this).addClass('missing-score');
          inputs_missing_scores_count++;
        }
        else {
          $(this).removeClass('missing-score');
        }
      });

      if(inputs_missing_scores_count > 0)
      {
        if(confirm('Some of your scoring criteria are missing values. Choose "OK" to submit your scores as-is. Choose "Cancel" to stop submission and continue entering your scores.') == false)
        {
          e.preventDefault();
        }

      }

    });

    $('body').popover({
      container: 'body',
      selector: '[data-toggle="popover"]'
    });

    $('.selectize').selectize();

});

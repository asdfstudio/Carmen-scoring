var ScheduleBuilder = (function () {
  var statusContainer

  var setStatusMessage = function (message) {
    // this.statusContainer.html(message);
  }

  var init = function () {
    this.statusContainer = $('.schedule-builder-status-message')

    $('.schedule-builder-list').sortable({
      connectWith: '.schedule-builder-list',
      items: 'li.schedule-item',
      placeholder: 'schedule-item placeholder',
      // update: this.handleListUpdate(event, ui)
      update: function (event, ui) {
        // this.setStatusMessage('Schedule changed');
        $('.schedule-builder-container').addClass('is-dirty')
      }
    })

    $('.item_name').on('blur', function () {
      $('.schedule-builder-container').addClass('is-dirty')
    })
  }

  /* var handleListUpdate = function(event, ui) {
    this.setStatusMessage('Schedule changed');
    $('.schedule-builder-container').addClass('is-dirty');
  }; */

  var save = function (url) {
    // this.setStatusMessage('Saving..');

    var items = $('ul.schedule li')
    var data = []

    items.each(function (index, element) {
      var scheduleItem = {}
      scheduleItem.performance_order = index + 1
      scheduleItem.round_id = $(this).data('round-id')
      scheduleItem.choir_id = $(this).data('choir-id')
      scheduleItem.division_id = $(this).data('division-id')
      scheduleItem.award_id = $(this).data('award-id')
      scheduleItem.caption_id = $(this).data('caption-id')
      scheduleItem.rank = $(this).data('rank')
      scheduleItem.is_rating = typeof $(this).data('rating') === 'undefined' ? 0 : 1;
      scheduleItem.scheduled_time = $(this).find('input.scheduled_time').val()
      scheduleItem.name = $(this).find('input.item_name').val()
      data.push(scheduleItem)
    })

    // console.log(data);

    var request = {
      data: JSON.stringify({ 'items': data }),
      dataType: 'json',
      contentType: 'application/json',
      method: 'POST',
      url: url,
      complete: function (xhr, status) {
        console.log('complete: ' + status)
      },
      success: this.handleSuccess(data, status),
      error: function (xhr, status) {
      }
    }

    return $.ajax(request)
  }

  var handleSuccess = function (data, status) {
    // console.log(data);
    // this.setStatusMessage('Saved');
    $('.schedule-builder-container').removeClass('is-dirty')
  }

  return {
    init: init,
    save: save,
    setStatusMessage: setStatusMessage,
    handleSuccess: handleSuccess
    // handleListUpdate: handleListUpdate
  }
})()

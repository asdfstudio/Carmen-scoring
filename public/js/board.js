// $(document).ready(function() {

var Modal = (function () {
  var modal = $('#modal')
  var modalCover = $('#modal-cover')

  var open = function (formHtml) {
    // Populate form in modal
    modal.html(formHtml)
    // Show page cover
    modalCover.show()
    // Show modal
    modal.show()
  }

  var close = function () {
    // Show page cover
    modalCover.hide()
    // Show modal
    modal.hide()
  }

  return {
    open: open,
    close: close
  }
})()

//
// Card
var Card = (function () {
  var createCard = function (type, data) {
    var prototypeCard = $('.card-prototype[data-resource-type="' + type + '"]')
    // clone form
    var card = prototypeCard.clone(true, true)
    card.removeClass('card-prototype')
    card.show()
    card.wrap('<div>')
    return Mustache.render(card.parent().html(), data)
  }

  var renderCard = function (type, id, html) {
    var existingCard = this.getExistingCard(type, id)

    if (existingCard.length) {
      return existingCard.replaceWith(html)
    } else {
      return $('ul.cards[data-resource-type="' + type + '"]').append(html)
    }
  }

  var createAndRenderCard = function (type, data) {
    var html = this.createCard(type, data)
    return this.renderCard(type, data.id, html)
  }

  var removeCard = function (type, id) {
    var existingCard = this.getExistingCard(type, id)

    if (existingCard) {
      return existingCard.remove()
    }
  }

  var getExistingCard = function (type, id) {
    var existingCard = $('.card[data-resource-type="' + type + '"][data-resource-id="' + id + '"]')

    if (existingCard.length > 0) {
      return existingCard
    }

    return false
  }

  return {
    createCard: createCard,
    renderCard: renderCard,
    createAndRenderCard: createAndRenderCard,
    getExistingCard: getExistingCard,
    removeCard: removeCard
  }
})()

//
// listClass
var List = (function () {
  var updateCounter = function (type) {
    var newCount = $('.card[data-resource-type="' + type + '"]:not(.card-prototype)').length
    $('.card-count[data-resource-type="' + type + '"]').html(newCount)
  }

  return {
    updateCounter: updateCounter
  }
})()

//
// Form
var Form = (function () {
  var theForm

  var getForm = function (type, id, resource) {
    var prototypeForm = $('.add-resource-form-prototype[data-resource-type="' + type + '"]')

    var form = prototypeForm.clone()

    // tweak
    $(form).removeClass('add-resource-form-prototype')

    // tweak form action
    if (id) {
      var action = $(form).attr('action')
      $(form).attr('action', action + '/' + id)
    }

    this.theForm = $(form)

    $(form).show()
    form.wrap('<div>')
    return Mustache.render(form.parent().html(), resource)
  }

  var buildRequest = function (form) {
    var resourceType = form.data('resource-type')

    var request = {
      data: form.serialize(),
      dataType: 'json',
      method: form.attr('method'),
      url: form.attr('action'),
      complete: function (xhr, status) {
        Resource.handleSaveComplete(status)
      },
      success: function (data, status) {
        Resource.handleSaveSuccess(resourceType, data, status)
      },
      error: function (xhr, status) {
        Resource.handleSaveError(status)
      }
    }

    return request
  }

  var buildRemoveRequest = function (link) {
    var resourceType = $(link).data('resource-type')
    var id = $(link).data('resource-id')
    var token = $(link).data('csrf-token')
    var data = {'_token': token, '_method': 'DELETE' }

    var request = {
      data: data,
      dataType: 'json',
      method: 'POST',
      url: $(link).attr('href'),
      complete: function (xhr, status) {
        Resource.handleSaveComplete(status)
      },
      success: function (data, status) {
        Resource.handleRemoveSuccess(resourceType, id, data, status)
      },
      error: function (xhr, status) {
        Resource.handleSaveError(status)
      }
    }

    return request
  }

  return {
    getForm: getForm,
    buildRequest: buildRequest,
    buildRemoveRequest: buildRemoveRequest,
    theForm: theForm
  }
})()

//
// Resource
var Resource = (function () {
  var add = function (type) {
    var html = Form.getForm(type, false, {})
    Modal.open(html)

    if (type == 'choir') {
      ChoirForm.init(Form.theForm)
    } else if (type == 'judge') {
      JudgeForm.init(Form.theForm)
    }

    var eventName = 'resourceadd' + type
    $(document.body).trigger(eventName)
  }

  var edit = function (type, id, resource) {
    var html = Form.getForm(type, id, resource)
    Modal.open(html)

    if (type == 'choir') {
      ChoirForm.init(Form.theForm)
    } else if (type == 'judge') {
      JudgeForm.init(Form.theForm)
    }
  }

  var remove = function (link) {
    var request = Form.buildRemoveRequest(link)
    console.log(request)
    $.ajax(request)
  }

  var save = function (form) {
    var request = Form.buildRequest(form)
    $.ajax(request)
  }

  var handleSaveSuccess = function (type, data, status) {
    Card.createAndRenderCard(type, data)
    List.updateCounter(type)
  }

  var handleRemoveSuccess = function (type, id, data, status) {
    Card.removeCard(type, id)
    List.updateCounter(type)
  }

  var handleSaveError = function (status) {
    console.log(status)
  }

  var handleSaveComplete = function (status) {
    Modal.close()
  }

  return {
    add: add,
    edit: edit,
    remove: remove,
    save: save,
    handleSaveSuccess: handleSaveSuccess,
    handleSaveError: handleSaveError,
    handleSaveComplete: handleSaveComplete,
    handleRemoveSuccess: handleRemoveSuccess
  }
})()

// });

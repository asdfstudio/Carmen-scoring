var isClickedNewJudge = false;
var isBoard = false;
var isClickedNewChoir = false;
var isClickedNewSchool = false;

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

  var bulkCreateAndRenderCard = function (type, data) {
    console.log('data:', data)
    if(!Array.isArray(data)) return;
    else if( data.length === 0) {
      dgToast('info', 'There are no judges to import!');
      return;
    }
    data.forEach(element => {
      var html = this.createCard(type, element)
      return this.renderCard(type, element.id, html)
    });
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
    bulkCreateAndRenderCard: bulkCreateAndRenderCard,
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
    var prototypeForm
    if(type === 'import')
      prototypeForm = $('.import-resource-form-prototype')
    else
     prototypeForm = $('.add-resource-form-prototype[data-resource-type="' + type + '"]')

    var form = prototypeForm.clone()
    form.find('input').each(function() {
      $(this).removeAttr('disabled')
    })

    // tweak
    $(form).removeClass('add-resource-form-prototype')
    $(form).removeClass('import-resource-form-prototype')

    // tweak form action
    if (id) {
      var action = $(form).attr('action')
      $(form).attr('action', action + '/' + id)
    }

    this.theForm = $(form)

    // To prevent id duplicates
    // remove "proto_" string from <for & id> attribute in the case of IMPORT JUDGE
    if(type === 'import') {
      $(form).find('label').each(function() {
        const forStr = $(this).attr('for');
        if((forStr || "").includes('proto_')) {
          $(this).attr('for', forStr.slice(6));
        }
      });
      $(form).find('input').each(function() {
        const idStr = $(this).attr('id');
        if((idStr || "").includes('proto_')) {
          $(this).attr('id', idStr.slice(6));
        }
      });

      // add class
      $(form).addClass('dg-import-resource-form');
    }

    // add "modal_" to <for & id> attribute in case of ADD a JUDGE
    if(type === 'judge') {

      $(form).find('select').attr('id', 'modal_' + $(form).find('select').attr('id'));

      $(form).find('div.choice-container input[type="checkbox"]').each(function() {
        const idStr = $(this).attr('id');
        if(idStr.includes('caption_id_')) {
          $(this).attr('id', `modal_${idStr}`);
          if($(this).siblings('label')) {
            $(this).siblings('label').attr('for', `modal_${idStr}`);
          }
        }
      })

      $(form).find('div.new_judge_container label.control-label').each(function() {
        const forStr = $(this).attr('for');
        if(forStr.includes('judge[')) {
          $(this).attr('for', `modal_${forStr}`);
          if($(this).siblings('input')) {
            $(this).siblings('input').attr('id', `modal_${forStr}`);
          }
        }
      })

      // delete submit button
      $(form).find('button[type="submit"]').remove();

      $(form).addClass('dg-mt-20');

    }

    // add "modal_" to <for & id> attribute in case of ADD a Choir
    if(type === 'choir') {

      // $(form).find('select#choir_id').attr('id', 'modal_' + $(form).find('select#choir_id').attr('id'));

      $(form).find('div.new_choir_container input, div.new_choir_container select, div.existing_choir_container select').each(function() {
        const idStr = $(this).attr('id');
        $(this).attr('id', `modal_${idStr}`);
        if($(this).siblings('label')) {
          $(this).siblings('label').attr('for', `modal_${idStr}`);
        }
      })

      // delete submit button
      $(form).find('button[type="submit"]').remove();

      const formId = $(form).attr('id');
      $(form).attr('id', `modal_${formId}`)
      $(form).addClass('dg-mt-20');
      $(form).addClass('dg-mb-80');

    }
    //

    $(form).show()
    form.wrap('<div>')
    return Mustache.render(form.parent().html(), resource)
  }

  var buildRequest = function (form) {
    var resourceType = form.data('resource-type')
    var resourceAction = form.data('resource-action')
    if(!resourceAction) resourceAction = 'add'
    var request = {
      data: form.serialize(),
      dataType: 'json',
      method: form.attr('method'),
      url: form.attr('action'),
      complete: function (xhr, status) {
        Resource.handleSaveComplete(status)
      },
      success: function (data, status) {
        Resource.handleSaveSuccess(resourceType, data, status, resourceAction)
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

    // if (type == 'choir') {
    //   ChoirForm.init(Form.theForm)
    // } else if (type == 'judge') {
    //   JudgeForm.init(Form.theForm)
    // }

    // var eventName = 'resourceadd' + type
    // $(document.body).trigger(eventName)
  }

  var importt = function (type) {
    var html = Form.getForm('import', false, {})
    Modal.open(html)
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
    $.ajax(request)
  }

  var save = function (form) {
    var request = Form.buildRequest(form)
    $.ajax(request)
  }

  var handleSaveSuccess = function (type, data, status, action) {
    action === 'add' ? Card.createAndRenderCard(type, data) : Card.bulkCreateAndRenderCard(type, data)
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
    importt: importt,
    edit: edit,
    remove: remove,
    save: save,
    handleSaveSuccess: handleSaveSuccess,
    handleSaveError: handleSaveError,
    handleSaveComplete: handleSaveComplete,
    handleRemoveSuccess: handleRemoveSuccess
  }
})()

// -dg-
$(document).ready(function() {

  // add a choir, judge, round
  $('.add-resource').on('click', function(e) {
    e.preventDefault();
    var type = $(this).data('resource-type');
    console.log('type:', type)

    // Resource.add(type);

    // -dg- add a judge
    if(type === 'judge') {
      const fHtml = Form.getForm('judge', false, {});

      Swal.fire({
        title: 'Add a Judge',
        html: fHtml,
        showCancelButton: true,
        confirmButtonText: "Add a Judge",
        focusConfirm: false,
        showLoaderOnConfirm: true,
        allowOutsideClick: () => !Swal.isLoading(),
        onOpen: () => {
          JudgeForm.init($('.dg-add-judge-form'));
        },
        preConfirm: (result) => {
          const url = $('form.dg-add-judge-form:not(.add-resource-form-prototype)').attr('action');
          const formData = $('form.dg-add-judge-form:not(.add-resource-form-prototype)').serialize();
          const formDataObj = JSON.parse(JSON.stringify($('form.dg-add-judge-form:not(.add-resource-form-prototype').serializeArray())) || [];
          let existingJudgeIds = [];
          $('.judges.cards.list-group > li:not(:first)').each(function() {
            existingJudgeIds.push($(this).data('resource-id'));
          });
          const emailRegEx = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          // console.log('existing judge:', existingJudgeIds)
          // console.log('formData:', formData)
          // console.log('formdata obj:', formDataObj)
          if(result) {
            if(!formDataObj.some(({name}) => name === 'caption_id[]')) {
              // check whether captions are selected or not,
              Swal.showValidationMessage('Request failed: Please choose captions to score!');
            }
            else if(!isClickedNewJudge) {
              if(formData.includes('&judge_id=&')) {
                // check judge choice,
                Swal.showValidationMessage('Request failed: There is no selected judge!')
              }
              else if(existingJudgeIds.findIndex(judgeId => judgeId && `${judgeId}` === (formDataObj[1] || {}).value) > -1) {
                // check existance,
                Swal.showValidationMessage('Request failed: The judge you selected already belongs to this division! Try again!')
              }
              else {
                // success
                return new Promise(function(resolve, reject) {
                  $.ajax({
                    data: formData,
                    dataType: 'json',
                    method: 'POST',
                    url: url,
                  }).done(resolve).fail(reject);
                });
              }
            }
            else {
              if(!(formDataObj[2] || {}).value) {
                Swal.showValidationMessage('Request failed: First Name is required!')
              }
              else if(!(formDataObj[3] || {}).value) {
                Swal.showValidationMessage('Request failed: Last Name is required!')
              }
              else if(!(formDataObj[4] || {}).value) {
                Swal.showValidationMessage('Request failed: Email is required!')
              }
              else if(!emailRegEx.test((formDataObj[4] || {}).value.trim().toLowerCase())) {
                Swal.showValidationMessage('Request failed: Invalid Email address!')
              }
              else {
                // success
                return new Promise((resolve, reject) => {
                  $.ajax({
                    data: formData,
                    dataType: 'json',
                    method: 'POST',
                    url: url,
                  }).done(resolve).fail(reject);
                });
              }
            }
          }
        }
      })
      .then((result) => {
        isClickedNewJudge = false;
        // console.log('result:', result)
        if ((result.value || {}).status === 'success') {
          Resource.handleSaveSuccess('judge', result.value.data, 200, 'add')
        }
        else if((result.value || {}).status === 'failed') {
          if(result.value.errors.includes('users_email_unique')) {
            dgSwalNotify('Failed', 'Duplicated email address! Input another email!', 'error');
          }
          else {
            console.log('error:', result.value.errors)
            dgSwalNotify('Failed', 'Something went wrong!', 'error');
          }
        }
      })
      .catch((err) => {
        console.log('error:', err)
        isClickedNewJudge = false;
        if(err) dgSwalNotify('Failed', 'Something went wrong!', 'error');
      });
    }
    else if(type === 'choir') {
      // -dg- add a choir
      const fHtml = Form.getForm('choir', false, {});

      Swal.fire({
        title: 'Add an Ensemble',
        html: fHtml,
        showCancelButton: true,
        confirmButtonText: "Add an Ensemble",
        focusConfirm: false,
        showLoaderOnConfirm: true,
        allowOutsideClick: () => !Swal.isLoading(),
        onOpen: () => {
          ChoirForm.init($('.dg-add-choir-form'));
        },
        preConfirm: (result) => {
          const url = $('form.dg-add-choir-form:not(.add-resource-form-prototype)').attr('action');
          const formData = $('form.dg-add-choir-form:not(.add-resource-form-prototype)').serialize();
          const formDataObj = JSON.parse(JSON.stringify($('form.dg-add-choir-form:not(.add-resource-form-prototype').serializeArray())) || [];
          let existingChoirIds = [];
          $('.choirs.cards.list-group > li:not(:first)').each(function() {
            existingChoirIds.push($(this).data('resource-id'));
          });
          const emailRegEx = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          // console.log('existing judge:', existingChoirIds)
          // console.log('formData:', formData)
          // console.log('formdata obj:', formDataObj)
          if(result) {
            if(!isClickedNewChoir) {
              if(formData.includes('&choir_id=&')) {
                // check choir choice,
                Swal.showValidationMessage('Request failed: There is no selected choir!')
              }
              else if(existingChoirIds.findIndex(choirId => choirId && `${choirId}` === (formDataObj[1] || {}).value) > -1) {
                // check existance,
                Swal.showValidationMessage('Request failed: The choir you selected already belongs to this division! Try again!')
              }
              else {
                // success
                return new Promise(function(resolve, reject) {
                  $.ajax({
                    data: formData,
                    dataType: 'json',
                    method: 'POST',
                    url: url,
                  }).done(resolve).fail(reject);
                });
              }
            }
            else {
              if(!(formDataObj[2] || {}).value) {
                Swal.showValidationMessage('Request failed: Choir Name is required!')
              }
              else if(!isClickedNewSchool && formData.includes('&school_id=&')) {
                // check school choice,
                Swal.showValidationMessage('Request failed: There is no selected school!')
              }
              else if(isClickedNewSchool && !(formDataObj[4] || {}).value) {
                Swal.showValidationMessage('Request failed: School Name is required!')
              }
              else if(isClickedNewSchool && !(formDataObj[5] || {}).value) {
                Swal.showValidationMessage('Request failed: School city is required!')
              }
              else if(isClickedNewSchool && !(formDataObj[6] || {}).value) {
                Swal.showValidationMessage('Request failed: School state is required!')
              }
              else if((formDataObj[7] || {}).name === 'director[person_id]' && !(formDataObj[7] || {}).value) {
                Swal.showValidationMessage('Request failed: There is no selected director!')
              }
              else if((formDataObj[7] || {}).name === 'director[first_name]' && !(formDataObj[7] || {}).value) {
                Swal.showValidationMessage('Request failed: First Name is Required!')
              }
              else if((formDataObj[7] || {}).name === 'director[first_name]' && !(formDataObj[8] || {}).value) {
                Swal.showValidationMessage('Request failed: Last Name is Required!')
              }
              else if((formDataObj[7] || {}).name === 'director[first_name]' && !(formDataObj[9] || {}).value) {
                Swal.showValidationMessage('Request failed: Email is Required!')
              }
              else if((formDataObj[7] || {}).name === 'director[first_name]' && !emailRegEx.test((formDataObj[9] || {}).value.trim().toLowerCase())) {
                Swal.showValidationMessage('Request failed: Invalid Email address!')
              }
              else if(!(formDataObj[7] || {}).name) {
                Swal.showValidationMessage('Request failed: Invalid input!')
              }
              else {
                // success
                return new Promise((resolve, reject) => {
                  $.ajax({
                    data: formData,
                    dataType: 'json',
                    method: 'POST',
                    url: url,
                  }).done(resolve).fail(reject);
                });
              }
            }
          }
        }
      })
      .then((result) => {
        isClickedNewChoir = false;
        isClickedNewSchool = false;
        // console.log('result:', result)
        if ((result.value || {}).status === 'success') {
          Resource.handleSaveSuccess('choir', result.value.data, 200, 'add')
        }
        else if((result.value || {}).status === 'failed') {
          if(result.value.errors.includes('users_email_unique')) {
            dgSwalNotify('Failed', 'Duplicated email address! Input another email!', 'error');
          } else if (result.value.errors.includes('choir_in_round')) {
            dgSwalNotify('Failed', 'Request failed: The choir you selected already belongs to this round.', 'error');
          } else {
            console.log('error:', result.value.errors)
            dgSwalNotify('Failed', 'Something went wrong!', 'error');
          }
        }
      })
      .catch((err) => {
        console.log('error:', err)
        isClickedNewChoir = false;
        isClickedNewSchool = false;
        if(err) dgSwalNotify('Failed', 'Something went wrong!', 'error');
      });
    }
    else {
      Resource.add(type);
    }

  });

  // import judges
  $('.import-resource').on('click', function(e) {
    e.preventDefault();
    // Resource.importt('import');

    // -dg-
    const fHtml = Form.getForm('import', false, {});
    Swal.fire({
      title: 'Choose a round to import judges from',
      html: fHtml,
      showCancelButton: true,
      confirmButtonText: "Import Judges",
      focusConfirm: false,
      showLoaderOnConfirm: true,
      allowOutsideClick: () => !Swal.isLoading(),
      preConfirm: (result) => {
        const url = $('form.dg-import-resource-form').attr('action');
        let formData = $('form.dg-import-resource-form').serialize();
        const isValid = formData.includes('&id=');
        if (result && !isValid) {
          Swal.showValidationMessage('Request failed: There are no selected divisions!');
        }
        else if (result && isValid){
          return new Promise(function(resolve, reject) {
            $.ajax({
              data: formData,
              dataType: 'json',
              method: 'POST',
              url: url,
            }).done(resolve).fail(reject);
          });
        }
      }
    })
    .then((result) => {
      console.log('result:', result)
      if (result.value) {
        Resource.handleSaveSuccess('judge', result.value, 200, 'import')
      }
    })
    .catch(err => {
      console.log('error:', err)
      if(err) dgSwalNotify('Failed', 'Something went wrong!', 'error');
    });
  });

  // edit captions for judge
  $('ul').on('click', 'a.edit-resource', function(e) {
    e.preventDefault();

    const arrAllCaptions = $(this).data('all-captions').split('-');
    const arrJudgeCaptionsId = $(this).data('captions').split('-');

    let fHtml = '<form id="swal_edit_judge_form">';
    for (let i = 0; i < arrAllCaptions.length; i += 2) {
      const id = arrAllCaptions[i];
      const name = arrAllCaptions[i+1];
      if(id) {
        const idx = (arrJudgeCaptionsId || []).findIndex(element => element === id);
        fHtml += `<div class="d-flex"><input id="dg_modal_caption_${id}" class="dg-mr-4" type="checkbox" name="caption_id[]" value="${id}" ${idx>-1 ? 'checked="checked"' : ''}/>
                  <label for="dg_modal_caption_${id}">${name}</label></div>`;
      }
    }
    fHtml += '</form>';

    const judgeName = $(this).parent().siblings('.name-div').text();

    Swal.fire({
      title: `Choose captions for ${judgeName} to score`,
      html: fHtml,
      showCancelButton: true,
      confirmButtonText: "Update",
      focusConfirm: false,
      showLoaderOnConfirm: true,
      allowOutsideClick: () => !Swal.isLoading(),
      preConfirm: (result) => {
        const url = $(this).attr('href');
        let formData = $('form#swal_edit_judge_form').serialize();

        if (result && !formData) {
          Swal.showValidationMessage('Request failed: There are no selected captions!');
        }
        else if (result && formData){
          formData += `&_token=${$(this).data('csrf-token')}&_method=PATCH`;
          return new Promise(function(resolve, reject) {
            $.ajax({
              data: formData,
              dataType: 'json',
              method: 'POST',
              url: url,
            }).done(resolve).fail(reject);
          });
        }
      }
    })
    .then((result) => {
      console.log('result:', result)
      if (result.value) {
        dgToast('success', `Captions for <b>${judgeName}</b> have been updated successfully!`);
        let captionsEl = '';
        let captions = '';
        result.value.forEach(element => {
          captionsEl += `<li class="background-color-${element.color_id} caption label">${element.name}</li>`;
          captions += `${element.id}-`;
        });
        console.log('captions:', captions)
        $(this).parent().siblings('ul').html(captionsEl);
        $(this).attr('data-captions', captions);
        $(this).data('captions', captions);
      }
    })
    .catch(err => {
      console.log('error:', err)
      if(err) dgSwalNotify('Failed', 'Something went wrong!', 'error');
    });
  });

  // change password
  $('.change-password').on('click', function(e) {
    e.preventDefault();
    const judge_id_cnt = $('.board-list.judges span.card-count').text();
    if(judge_id_cnt === '0') {
      dgSwalNotify('Oops...', 'There are no judges!', 'info');
    }
    else {
      let ids_str = '';
      $('.judges.cards.list-group > li:not(:first)').each(function() {
        ids_str += $(this).data('resource-id') + '-';
      });
      const fHtml = `<div class="text-left dg-mb-4 dg-mt-20">New Password:</div>
                    <input type="password" id="dg_password_input" class="form-control dg-mb-20" style="width:100%"/>
                    <div class="text-left dg-mb-4">Password Confirm:</div>
                    <input type="password" id="dg_password_confirm_input" class="form-control dg-mb-20" style="width:100%"/>`;
      Swal.fire({
        title: 'Change password',
        html: fHtml,
        showCancelButton: true,
        confirmButtonText: "Update",
        focusConfirm: false,
        showLoaderOnConfirm: true,
        allowOutsideClick: () => !Swal.isLoading(),
        preConfirm: (result) => {
          const pwd = $('#dg_password_input').val().trim();
          const pwd_confirm = $('#dg_password_confirm_input').val().trim();

          if(result) {
            if (!pwd) {
              Swal.showValidationMessage('Request failed: New Password is required!');
            }
            else if (!pwd_confirm) {
              Swal.showValidationMessage('Request failed: Confirmation is required!');
            }
            else if (pwd !== pwd_confirm){
              Swal.showValidationMessage('Request failed: Password confirmation does not match!');
            }
            else {
              const token = $('meta[name="_token"]').attr('content');
              return new Promise(function(resolve, reject) {
                $.ajax({
                  data: `_token=${token}&_method=PUT&password=${pwd}&ids=${ids_str}`,
                  dataType: 'json',
                  method: 'POST',
                  url: $('.base-url-div').html() + '/organizer/user/password/mass',
                }).done(resolve).fail(reject);
              });
            }
          }
        }
      })
      .then((result) => {
        console.log('result:', result)
        if (result.value) {
          dgSwalNotify('Success!', 'Password have been updated successfully!', 'success')
        }
      })
      .catch(err => {
        console.log('error:', err)
        if(err) dgSwalNotify('Failed', 'Something went wrong!', 'error');
      });
    }
  });

  // remove choir, judge
  $('ul').on('click', 'a.remove-resource', function(e) {
    e.preventDefault();

    // Resource.remove(this);
    const resourceType = $(this).data('resource-type')
    const resourceId = $(this).data('resource-id')

    Swal.fire({
      title: 'Are you sure?',
          text: 'Are you sure you want to remove the ' + resourceType + '?',
      icon: "warning",
      showCancelButton: true,
      focusCancel: true,
      customClass: {
        container: 'dg-confirm-container',
      },
      confirmButtonText: '<i class="fa fa-check"></i> OK',
      cancelButtonText: '<i class="fa fa-times"></i> Cancel',
      confirmButtonColor: '#7F4091',
      showLoaderOnConfirm: true,
      allowOutsideClick: () => !Swal.isLoading(),
      preConfirm: (result) => {
        const url = $(this).attr('href')
        const token = $(this).data('csrf-token')
        const data = {'_token': token, '_method': 'DELETE' }

        if (result){
          return new Promise(function(resolve, reject) {
            $.ajax({
              data,
              dataType: 'json',
              method: 'POST',
              url,
            }).done(resolve).fail(reject);
          });
        }
      }
    })
    .then((result) => {
      console.log('result:', result)
      if (result.value) { // deleted resource id
        dgToast('success', `The ${resourceType} have been removed successfully!`);
        Card.removeCard(resourceType, resourceId);
        List.updateCounter(resourceType);
      }
    })
    .catch(err => {
      console.log('error:', err)
      if(err) dgSwalNotify('Failed', 'Something went wrong!', 'error');
    });
  });

  // $('form.remove-resource').on('submit', function(e) {
  //   e.preventDefault();
  //   Resource.remove(this);
  // });

  $('#modal').on('submit', 'form', function(e) {
    e.preventDefault();
    var form = $(this);
    Resource.save(form);
  });

  $('#modal-cover').on('click', function(e) {
    e.preventDefault();
    Modal.close();
  });

  /*$('body').on('ready', '.add-resource-form', function(e) {
    e.preventDefault();
    console.log('resource form ready');
    ChoirForm.init();
  });*/

  $('body').on('click', '.toggle-new-choir-container', function(e) {
    e.preventDefault();
    ChoirForm.init($(this).parents('form'));
    ChoirForm.showNewChoirForm();
    isClickedNewChoir = true;
    if(choirSelectize) choirSelectize[0].selectize.clear();
  });

  $('body').on('click', '.toggle-new-school-container', function(e) {
    e.preventDefault();
    ChoirForm.init($(this).parents('form'));
    ChoirForm.showNewSchoolForm();
    isClickedNewSchool = true;
  });

  $('body').on('click', '.toggle-new-judge-container', function(e) {
    e.preventDefault();
    // console.log('haha-board')
    JudgeForm.init($(this).parents('form'));
    JudgeForm.showNewJudgeForm();
    isClickedNewJudge = true;
  });

  $('body').on('change', 'input[name="choir_source"]', function(e) {
    e.preventDefault();
    RoundForm.init($(this).parents('form'));
    RoundForm.toggleChoirSource();
  });
});


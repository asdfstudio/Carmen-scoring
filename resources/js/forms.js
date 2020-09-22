var ChoirForm = (function () {
  var form

  var init = function (form) {
    this.form = form

    choirSelectize = $(form).find('#modal_choir_id').selectize()

    schoolId = $('#modal_school_id')
    personId = $('#modal_person-id')
    toggleNewDirector = $(form).find('.toggle-new-director')
    directorSearchGroup = $(form).find('.director-search-group')
    directorCreateGroup = $(form).find('.director-create-group')
    isBoard = true;
    initSelectize()
  }

  var showNewChoirForm = function () {
    this.form.find('.new_choir_container').show()
    this.form.find('.new_school_container').hide()
    this.form.find('.existing_choir_container').hide()
    this.form.find('.toggle-new-choir-container').hide()
  }

  var showNewSchoolForm = function () {
    this.form.find('.new_school_container').show()
    this.form.find('.existing_school_container').hide()
    this.form.find('.toggle-new-school-container').hide()
  }

  return {
    init: init,
    showNewChoirForm: showNewChoirForm,
    showNewSchoolForm: showNewSchoolForm
  }
})()

var JudgeForm = (function () {
  var form

  var init = function (form) {
    this.form = form
    // judgeSelectize = $('#modal').find('.judge_id').selectize({
    judgeSelectize = $(form).find('#modal_judge_id').selectize({
      placeholder: 'Select a judge...',
      allowEmptyOption: true,
    })
    judgeSelectize[0].selectize.clear();
  }

  var showNewJudgeForm = function () {
    this.form.find('.new_judge_container').show()
    this.form.find('.existing_judge_container').hide()
    this.form.find('.toggle-new-judge-container').hide()
  }

  return {
    init: init,
    showNewJudgeForm: showNewJudgeForm
  }
})()

var RoundForm = (function () {
  var form

  var init = function (form) {
    this.form = form
    // roundSelectize = $('#modal').find('.judge_id').selectize();
  }

  var toggleChoirSource = function () {
    var choir_source = this.form.find('input[name="choir_source"]:checked').val()

    if (choir_source == 'all') {
      this.form.find('input[name="max_choirs"]').val(0)
      this.form.find('div.max-choirs-container').hide()
      this.form.find('div.rounds-container').hide()
    } else {
      this.form.find('div.max-choirs-container').show()
      this.form.find('div.rounds-container').show()
    }
  }

  return {
    init: init,
    toggleChoirSource: toggleChoirSource
  }
})()

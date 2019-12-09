// Customize the behaviour of the form that creates or edits a user or person.

// webkitURL is deprecated but nevertheless
URL = window.URL || window.webkitURL

var gumStream					// stream from getUserMedia()
var rec					// Recorder.js object
var audioRecorder
var recorder
var recordButton = document.getElementById('recordButton')
var stopButton = document.getElementById('stopButton')
var recordData = []
var recordingsInProgress = 0

// eslint-disable-next-line no-unused-vars
function deleteRecording (id) {
  if (confirm('Are you sure you want to delete the record?') == true) {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
      }
    })
    $.ajax({
      url: '/organizer/recording/delete/' + id,
      method: 'DELETE',
      success: function (result) {
        location.reload()
      }
    })
  }
}

function startRecording (choirId, roundId, divisionId) {
  var input = document.getElementById('recordingInProgress')
  console.log('recordButton clicked')
  var button = $('#recordButton-' + choirId)
  var isRecording = parseInt(button.attr('data-recording')) || 0
  console.log('isRecording', isRecording)
  var count = parseInt(button.attr('data-count')) || 0
  console.log('count', count)
  recordData.choirId = choirId
  recordData.roundId = roundId
  recordData.divisionId = divisionId
  if (isRecording === 0) {
    recorder = new MicRecorder({
      bitRate: 128
    })
    /*
        Disable the record button until we get a success or fail from getUserMedia()
    */
    recorder
      .start()
      .then(() => {
        $('.rbutton').addClass('cancel')
        button.removeClass('cancel')
        button.text('Stop Recording')
        button.attr('data-recording', 1)
        button.attr('data-count', count + 1)
        input.value = parseInt(input.value) + 1
        console.log('Recording started')
      // something else
      })
      .catch(e => {
        if (e.name === 'NotFoundError') {
          alert('Please plugin your microphone')
        } else if (e.name === 'TypeError') {
          alert('Your browser does not support recording')
        } else {
          alert('something went wrong')
        }
        return false
      })
  } else {
    // stop recording
    console.log('stopping...')
    recorder.stop().getMp3().then(([buffer, blob]) => {
      const file = new File(buffer, 'music.mp3', {
        type: blob.type,
        lastModified: Date.now()
      })
      $('.rbutton').removeClass('cancel')
      button.text('Start Recording (' + count + ')')
      button.attr('data-recording', 0)
      uploadRecording(blob)
    }).catch((e) => {
      console.error(e)
    })
  }
}

function uploadRecording (blob) {
  var input = document.getElementById('recordingInProgress')
  var formData = new FormData()
  formData.append('division_id', recordData.divisionId)
  formData.append('round_id', recordData.roundId)
  formData.append('file', blob)
  formData.append('choir_id', recordData.choirId)
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    }
  })
  $('#sliderId-' + recordData.choirId).show()

  $.ajax({
    url: '/judge/recording/save',
    method: 'POST',
    data: formData,
    cache: false,
    contentType: false, // must, tell jQuery not to process the data
    processData: false,
    success: function (result) {
      input.value = parseInt(input.value) - 1
      $('#sliderId-' + recordData.choirId).hide()
    }
  })
}

$(document).ready(function () {
  window.onbeforeunload = function () {
    if (parseInt(document.getElementById('recordingInProgress').value) > 0) {
      return 'Upload in progress, navigating away from the page will lose recording. Are you sure you want to continue?'
    }
  }
  // eslint-disable-next-line no-undef
  Dropzone.autoDiscover = false
  $('#myAwesomeDropzone').dropzone({
    paramName: 'file', // The name that will be used to transfer the file
    maxFilesize: 500, // MB
    acceptedFiles: 'audio/*',
    addRemoveLinks: false,
    uploadprogress: function (file, response) {
      window.onbeforeunload = function () {
        return 'Upload in progress, navigating away from the page will lose recording. Are you sure you want to continue?'
      }
    }
  })
})

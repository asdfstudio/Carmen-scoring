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
var uploadsInProgress = 0

// eslint-disable-next-line no-unused-vars
function deleteRecording (id) {
  if (confirm('Are you sure you want to delete the recording?') == true) {
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
        recordingsInProgress++
        $('.rbutton').addClass('cancel')
        button.removeClass('cancel')
        button.text('Stop Recording')
        button.attr('data-recording', 1)
        button.attr('data-count', count + 1)
        console.log('Recording started')
      // something else
      })
      .catch(e => {
        console.error(e)
        if (e.name === 'NotFoundError') {
          alert('Please plugin your microphone')
        } else if (e.name === 'TypeError') {
          alert('Your browser does not support recording')
        } else {
          alert('Something went wrong')
        }
        return false
      })
  } else {
    // stop recording
    console.log('stopping...')
    recorder.stop().getMp3().then(([buffer, blob]) => {
      recordingsInProgress--
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
      warnRecordingSaveError();
    })
  }
}

function uploadRecording (blob) {
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
  uploadsInProgress++
  $.ajax({
    url: '/judge/recording/save',
    method: 'POST',
    data: formData,
    cache: false,
    contentType: false, // must, tell jQuery not to process the data
    processData: false,
    success: function (result) {
      console.log(result)
      $('#sliderId-' + recordData.choirId).hide()
      if(typeof result.url === 'undefined'){
        warnRecordingSaveError()
      }
    },
    error: function() {
      warnRecordingSaveError()
    },
    complete: function() {
      uploadsInProgress--
    }
  })
}

function warnRecordingSaveError () {
  alert('There was an error saving your recording to the server.  Please refresh this page and try again.')
}

function warnUploadRecordingError () {
  alert('There was an error uploading your file to the server.  Please refresh this page and try uploading the file again.')
}


$(document).ready(function () {
  window.onbeforeunload = function () {
    if (recordingsInProgress > 0) {
      return 'Recording in progress. Navigating away from the page will lose your recording. Are you sure you want to continue?'
    }
    if (uploadsInProgress > 0) {
      return 'Upload in progress. Navigating away from the page will lose your file. Are you sure you want to continue?'
    }
  }
  // eslint-disable-next-line no-undef
  Dropzone.autoDiscover = false
  $('#myAwesomeDropzone').dropzone({
    init: function() {
      this.on("success", function(file, response) {
        console.log(response);
        if(typeof response.url === 'undefined'){
          warnUploadRecordingError();
        }
        console.log('Response:', response);
      });
      this.on("error", function(file, errorMessage, xhr) {
        warnUploadRecordingError();
        console.log('Error Message:', errorMessage);
        console.log('XMLHttpRequest:', xhr);
      });
    },
    paramName: 'file', // The name that will be used to transfer the file
    maxFilesize: 500, // MB
    acceptedFiles: 'audio/*',
    addRemoveLinks: false
  })
})

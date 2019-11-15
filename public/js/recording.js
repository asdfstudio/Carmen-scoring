// Customize the behaviour of the form that creates or edits a user or person.

// webkitURL is deprecated but nevertheless
URL = window.URL || window.webkitURL

var gumStream 						// stream from getUserMedia()
var rec 							// Recorder.js object
var audioRecorder
// shim for AudioContext when it's not avb.
var AudioContext = window.AudioContext || window.webkitAudioContext
var audioContext // audio context to help us record

var recordButton = document.getElementById('recordButton')
var stopButton = document.getElementById('stopButton')

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
  console.log(choirId)
  console.log('recordButton clicked')
  var button = $('#recordButton-' + choirId)
  var isRecording = parseInt(button.attr('data-recording')) || 0
  console.log('isRecording', isRecording)
  var count = parseInt(button.attr('data-count')) || 0
  console.log('count', count)

  if (isRecording === 0) {
    /*
      Simple constraints object, for more advanced audio features see
      https://addpipe.com/blog/audio-constraints-getusermedia/
    */

    var constraints = { audio: true, video: false }

    /*
        Disable the record button until we get a success or fail from getUserMedia()
    */

    navigator.mediaDevices.getUserMedia(constraints)
      .then(function (stream) {
        console.log('getUserMedia() success, stream created, initializing Recorder.js ...')
        /*
          create an audio context after getUserMedia is called
          sampleRate might change after getUserMedia is called, like it does on macOS when recording through AirPods
          the sampleRate defaults to the one set in your OS for your playback device
        */
        audioContext = new AudioContext()
        /*  assign to gumStream for later use  */
        gumStream = stream

        /* use the stream */
        audioRecorder = new MediaRecorder(stream, {
          audioBitsPerSecond: 96000
        })
        // start the recording process
        audioRecorder.start()
        $('.rbutton').addClass('cancel')
        button.removeClass('cancel')
        button.text('Stop Recording')
        button.attr('data-recording', 1)
        button.attr('data-count', count + 1)
        console.log('Recording started')
      }).catch(function () {
        /* handle the error */
        alert('Please plugin your earphone')
      })
  } else {
    $('.rbutton').removeClass('cancel')
    button.text('Start Recording (' + count + ')')
    button.attr('data-recording', 0)
    var recordingData = []
    audioRecorder.ondataavailable = function (event) {
      recordingData = []
      recordingData.push(event.data)
    }
    audioRecorder.onstop = function (event) {
      console.log('Media recorder stopped')
      // stop microphone access
      gumStream.getAudioTracks()[0].stop()
      var blob = new Blob(recordingData, { type: 'audio/wav' })
      uploadRecording(blob, choirId, roundId, divisionId)
    }
    // tell the recorder to stop the recording
    audioRecorder.stop()
    console.log('Recording stopped')
  }
}
function uploadRecording (blob, choirId, roundId, divisionId) {
  var formData = new FormData()
  formData.append('division_id', divisionId)
  formData.append('round_id', roundId)
  formData.append('file', blob)
  formData.append('choir_id', choirId)

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    }
  })
  $.ajax({
    url: '/judge/recording/save',
    method: 'POST',
    data: formData,
    cache: false,
    contentType: false, // must, tell jQuery not to process the data
    processData: false,
    success: function (result) {

    }
  })
}

$(document).ready(function () {
  Dropzone.autoDiscover = false
  $('#myAwesomeDropzone').dropzone({
    paramName: 'file', // The name that will be used to transfer the file
    maxFilesize: 20, // MB
    acceptedFiles: 'audio/*',
    addRemoveLinks: false
  })
})

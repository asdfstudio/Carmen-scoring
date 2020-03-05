// Array to hold all audio recorder instances that may be on the page.
window.audioRecorers = []

// The audio recorder class that will be initialized for each audio recorder widget on the page.
function AudioRecorder (element) {

  /*================================================================================
      Properties
    ================================================================================*/

  this.widget = $(element)
  this.otherWidgets = $('.audio-recorder').not(this.widget)
  this.button = $(this.widget).find('.ar-control button')
  this.existingLabel = $(this.widget).find('.ar-existing')
  this.progressText = $(this.widget).find('.ar-progress-text')
  this.recordingTime = 0
  this.recordingTimer = null
  this.progressMeterBar = $(this.widget).find('.ar-meter-bar')
  this.micRecorder = new MicRecorder({bitRate: 128})
  this.choirId = parseInt(this.widget.data('choir')) || 0
  this.roundId = parseInt(this.widget.data('round')) || 0
  this.divisionId = parseInt(this.widget.data('division')) || 0
  this.existingRecordingCount = parseInt(this.widget.data('count')) || 0
  this.recordingInProgress = false
  this.uploadInProgress = false
  this.uploadProgressCounter = 0
  this.uploadSuccessDenouement = false


  /*================================================================================
      Event Listeners
    ================================================================================*/

  this.button.click((e) => {
    e.preventDefault()
    if(!this.widget.hasClass('disabled')){
      this.startRecording();
    }
  });

  $(window).on('beforeunload', () => {
    if (this.recordingInProgress) {
      return 'Recording in progress. Navigating away from the page will lose your recording. Are you sure you want to continue?'
    }
    if (this.uploadInProgress) {
      return 'Upload in progress. Navigating away from the page will lose your file. Are you sure you want to continue?'
    }
  })


  /*================================================================================
      Methods
    ================================================================================*/

  this.startRecording = () => {
    if (!this.recordingInProgress) {
      // Start recording.
      this.micRecorder.start()
        .then(() => {
          console.log('Recording started.')

          this.recordingInProgress = true
          this.startRecordingTime()

          this.otherWidgets.addClass('disabled')
          this.widget.addClass('recording')
        })
        .catch((error) => {
          console.error(error)

          if (error.name === 'NotFoundError') {
            alert('Please connect or enable your microphone.')
          } else if (error.name === 'TypeError') {
            alert('Your browser does not support recording.')
          } else {
            alert('There was an error when trying to record.')
          }
          return false
        })
    } else {
      // Stop recording.
      this.micRecorder.stop()
        .getMp3()
        .then(([buffer, blob]) => {
          console.log('Recording stopped.')

          this.recordingInProgress = false
          this.clearRecordingTime()

          this.widget.removeClass('recording').addClass('disabled')

          this.uploadRecording(blob)
        })
        .catch((error) => {
          console.error(error)

          this.warnRecordingSaveError();
        })
    }
  }

  this.uploadRecording = (blob) => {
    var formData = new FormData()
    formData.append('division_id', this.divisionId)
    formData.append('round_id', this.roundId)
    formData.append('choir_id', this.choirId)
    formData.append('file', blob)

    $.ajax(
      {
        url: '/judge/recording/save',
        method: 'POST',
        data: formData,
        cache: false,
        contentType: false, // must, tell jQuery not to process the data
        processData: false,
        xhr: () => {
          var xhr = new window.XMLHttpRequest();

          if(xhr.upload){
            xhr.upload.addEventListener('progress', this.uploadProgressHandler, false);
          }

          return xhr
        },
        beforeSend: (jqXHR, settings) => {
          jqXHR.setRequestHeader('X-CSRF-TOKEN', $('meta[name="_token"]').attr('content'))

          this.uploadInProgress = true
          this.updateProgressText()

          this.widget.addClass('uploading unknown')

          console.log('Uploading...')
        }
      }
    ).done(
      (result, textStatus, jqXHR) => {
        if(typeof result.url === 'undefined'){
          console.log('Upload resulted in server-side error.')

          this.warnUploadRecordingError()
        } else {
          console.log('Upload completed successfully.')

          this.existingRecordingCount++
          this.uploadSuccessDenouement = true

          this.widget.data('count', this.existingRecordingCount)
          this.progressMeterBar.css('width', '100%')
          this.widget.removeClass('unknown')
          this.existingLabel.text(this.existingRecordingCount + ' ' + (this.existingRecordingCount == 1 ? 'Recording' : 'Recordings') + ' on File')
        }

        console.log('Result:', result)
      }
    ).fail(
      (jqXHR, textStatus, errorThrown) => {
        console.log('Upload resulted in AJAX error.')
        console.log('jqXHR:', jqXHR)

        this.warnUploadRecordingError()
      }
    ).always(
      () => {
        this.uploadInProgress = false
        this.uploadProgressCounter = 0
        this.updateProgressText()

        if(this.uploadSuccessDenouement) {
          // After a successful upload, allow the widget to linger in the upload state for a moment
          // so that the user has time to understand that it is complete.
          setTimeout(() => {
            this.uploadSuccessDenouement = false
            this.updateProgressText()
            this.progressMeterBar.css('width', '')
            this.widget.removeClass('disabled uploading unknown')
            this.otherWidgets.removeClass('disabled')
          }, 2000)
        } else {
          // If this wasn't a successful upload, just reset the widget appearance.
          // The user already got a warning message.
          this.widget.removeClass('disabled uploading unknown')
          this.otherWidgets.removeClass('disabled')
        }
      }
    )
  }

  this.uploadProgressHandler = (progress) => {
    var percent = 0

    if(progress.lengthComputable){
      percent = Math.floor(progress.loaded / progress.total * 100)

      console.log('Upload progress:', percent + '%')

      if(percent < 100 || this.uploadProgressCounter > 0){
        this.progressMeterBar.css('width', percent + '%')
        this.widget.removeClass('unknown')
      }
    }

    this.uploadProgressCounter++
  }

  this.updateProgressText = () => {
    var formattedText
    if(this.recordingInProgress){
      var seconds = this.recordingTime
      var hours = Math.floor(seconds / 3600)
      seconds = seconds - (hours * 3600)
      var minutes = Math.floor(seconds / 60)
      seconds = seconds - (minutes * 60)
      formattedText = minutes.toString().padStart(2, '0') + ':' + seconds.toString().padStart(2, '0')
      if(hours){
        formattedText = hours + ':' + formattedText;
      }
    } else if(this.uploadInProgress){
      formattedText = 'UPLOADING'
    } else if(this.uploadSuccessDenouement){
      formattedText = 'COMPLETE'
    } else {
      formattedText = '--:--'
    }
    this.progressText.text(formattedText)
  }

  this.startRecordingTime = () => {
    this.updateProgressText()
    this.recordingTimer = setInterval(() => {
      this.recordingTime++
      this.updateProgressText()
    }, 1000)
  }

  this.clearRecordingTime = () => {
    clearInterval(this.recordingTimer)
    this.recordingTime = 0
    this.updateProgressText()
  }

  this.warnRecordingSaveError = () => {
    alert('There was an error saving your recording to the server.  Please refresh this page and try again.')
  }

  this.warnUploadRecordingError = () => {
    alert('There was an error uploading your file to the server.  Please refresh this page and try uploading the file again.')
  }

  this.deleteRecording = (id) => {
    if (confirm('Are you sure you want to delete the recording?') == true) {
      $.ajax({
        url: '/organizer/recording/delete/' + id,
        method: 'DELETE',
        beforeSend: (jqXHR, settings) => {
          jqXHR.setRequestHeader('X-CSRF-TOKEN', $('meta[name="_token"]').attr('content'))
        },
        success: (result) => {
          location.reload()
        }
      })
    }
  }
}


$(document).ready(function () {
  // Initialize all audio recorder widgets.
  $('.audio-recorder').each(function(i, element){
    window.audioRecorers.push(new AudioRecorder(element))
  })

  // Initialize the Dropzone.
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

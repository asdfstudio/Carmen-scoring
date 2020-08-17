// Array to hold all audio recorder instances that may be on the page.
window.audioRecorers = []

// The audio recorder class that will be initialized for each audio recorder widget on the page.
function AudioRecorder (element) {

  /*================================================================================
      Properties
    ================================================================================*/

  this.widget = $(element)
  this.otherWidgets = $('.audio-recorder').not(this.widget)
  this.mode = this.widget.data('mode')
  this.role = this.widget.data('role')
  this.canDelete = (this.role === 'organizer' || this.role === 'admin')
  this.controlButton = this.widget.find('.ar-control button')
  this.recorderTitle = this.widget.find('.ar-title')
  this.playbackTitle = this.widget.find('.ar-title-playback')
  this.playbackTitleSlot = this.playbackTitle.find('span')
  this.existingLabel = this.widget.find('.ar-existing')
  this.progressText = this.widget.find('.ar-progress-text')
  this.playlistClose = this.widget.find('.ar-playback-close')
  this.playlistContainer = this.widget.find('.ar-playlist')
  this.playlist = this.playlistContainer.find('ol')
  this.playlistItems = this.playlistContainer.find('ol > li')
  this.playlistPlayPause = this.playlistItems.find('.ar-playlist-play-pause')
  this.playlistDownload = this.playlistItems.find('.ar-playlist-download')
  this.playlistDelete = this.playlistItems.find('.ar-playlist-delete')
  this.playlistCurrentItem = null
  this.playlistCurrentPlayPause = null
  this.playlistCurrentAudio = null
  this.recordingTime = 0
  this.recordingTimer = null
  this.progressMeterBox = this.widget.find('.ar-meter-box')
  this.progressMeterBar = this.widget.find('.ar-meter-bar')
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
      Setup & Event Listeners
    ================================================================================*/

  this.playlistItemHTML = (recording) => {
    var maybeDeleteButton = this.canDelete ? '<button class="ar-playlist-delete" title="Delete Recording"></button>' : '';
    return '<li id="recording-' + recording.id + '" data-id="' + recording.id + '" data-url="' + recording.url + '"><audio><source src="' + recording.url + '"></audio><div class="ar-playlist-item-name">' + niceDate(recording.created_at) + '</div><div class="ar-playlist-functions"><button class="ar-playlist-play-pause" title="Play/Pause"></button><a class="ar-playlist-download" title="Download Recording" href="' + recording.url + '" download="' + niceDate(recording.created_at) + '" type="application/octet-stream"></a>' + maybeDeleteButton + '</div></li>'
  }

  if(this.playlist.length == 0){
    this.playlistContainer.append('<ol></ol>')
    this.playlist = this.playlistContainer.find('ol')

    if(this.playlistContainer.data('recordings')){
      var recordings = this.playlistContainer.data('recordings')
      for(var i = 0; i < recordings.length; i++){
        this.playlist.append(this.playlistItemHTML(recordings[i]))
      }
      this.playlistItems = this.playlistContainer.find('ol > li')
      this.playlistPlayPause = this.playlistItems.find('.ar-playlist-play-pause')
      this.playlistDownload = this.playlistItems.find('.ar-playlist-download')
      this.playlistDelete = this.playlistItems.find('.ar-playlist-delete')
    }

    if(!this.playlistItems.length){
      this.playlist.addClass('empty').append('<li>No recordings on file.</li>')
    }
  }

  if(this.mode === 'recorder'){
    this.controlButton.click((e) => {
      e.preventDefault()
      if(!this.widget.hasClass('disabled')){
        this.startRecording();
      }
    });
  } else {
    this.controlButton.remove()
  }

  this.existingLabel.click((e) => {
    if(this.widget.hasClass('playlist-expanded')){
      this.widget.removeClass('playlist-expanded');
    } else {
      this.widget.addClass('playlist-expanded');
    }
  })

  this.setupPlaylistPlayPause = () => {
    this.playlistPlayPause.off('click')

    this.playlistPlayPause.click((e) => {
      e.preventDefault()

      this.playlistCurrentItem = $(e.target).closest('li')

      // First, see if this is the initial playback click for this item.
      if(!this.playlistCurrentItem.hasClass('playing')){
        // Stop all audio players on the page.
        $('audio').each((index, audio) => {
          audio.pause()
          audio.currentTime = 0
          $(audio).off('timeupdate')
          $(audio).off('ended')
        })

        this.playlistItems.removeClass('playing')
        this.playlistCurrentItem.addClass('playing')

        this.playbackTitleSlot.text(this.playlistCurrentItem.find('.recording-name').text())

        if(!this.widget.hasClass('playback')){
          this.widget.addClass('playback disabled')
        }

        this.playlistPlayPause.removeClass('active')
        this.playlistCurrentPlayPause = this.playlistCurrentItem.find('.ar-playlist-play-pause')
        this.playlistCurrentPlayPause.addClass('active')

        this.playlistCurrentAudio = this.playlistCurrentItem.find('audio')[0]

        $(this.playlistCurrentAudio).on('timeupdate', (e) => {
          var formattedTime = this.formatTime(this.playlistCurrentAudio.currentTime)
          this.progressText.text(formattedTime)
          var percent = this.playlistCurrentAudio.currentTime / this.playlistCurrentAudio.duration * 100
          this.progressMeterBar.css('width', percent + '%')
        })

        $(this.playlistCurrentAudio).on('ended', (e) => {
          this.playlistCurrentPlayPause.removeClass('active')
        })

        this.progressMeterBox.click((e) => {
          console.log(e)
          console.log($(e.target).width())
          this.playlistCurrentAudio.currentTime = (e.offsetX / $(e.target).width()) * this.playlistCurrentAudio.duration
        })

        this.playlistCurrentAudio.play()
      } else {
        // If this item was already the "current" item (whether playing or paused), no setup is needed. Just handle controls.
        if(this.playlistCurrentAudio.paused || this.playlistCurrentAudio.ended){
          this.playlistCurrentPlayPause.addClass('active')
          this.playlistCurrentAudio.play()
        } else {
          this.playlistCurrentPlayPause.removeClass('active')
          this.playlistCurrentAudio.pause()
        }
      }
    })
  }

  this.setupPlaylistPlayPause()

  this.setupPlaylistDelete = () => {
    if(this.canDelete){
      this.playlistDelete.off('click')

      this.playlistDelete.click((e) => {
        e.preventDefault()
        var id = $(e.target).closest('li').data('id')
        this.deleteRecording(id)
      })
    }
  }

  this.setupPlaylistDelete()

  if(this.mode === 'recorder'){
    this.playlistClose.click((e) => {
      this.widget.removeClass('playback disabled')
      this.playbackTitleSlot.text('')
      this.playlistItems.removeClass('playing')
      this.playlistPlayPause.removeClass('active')
      $('audio').each((index, audio) => {
        audio.pause()
        audio.currentTime = 0
        $(audio).off('timeupdate')
        $(audio).off('ended')
      })
      this.progressText.text('--:--')
      this.progressMeterBar.css('width', '')
      this.progressMeterBox.off('click')
    })
  } else {
    this.playlistClose.remove()
  }

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
          //console.log('Recording started.')

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
          //console.log('Recording stopped.')

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

          //console.log('Uploading...')
        }
      }
    ).done(
      (result, textStatus, jqXHR) => {
        if(typeof result.url === 'undefined'){
          console.log('Upload resulted in server-side error.')
          console.log('Result:', result)
          this.warnUploadRecordingError()
        } else {
          //console.log('Upload completed successfully.')

          this.existingRecordingCount++
          this.uploadSuccessDenouement = true

          this.widget.data('count', this.existingRecordingCount)
          this.widget.attr('data-count', this.existingRecordingCount)
          this.progressMeterBar.css('width', '100%')
          this.widget.removeClass('unknown')
          this.existingLabel.text(this.existingRecordingCount + ' ' + (this.existingRecordingCount == 1 ? 'Recording' : 'Recordings') + ' on File')

          if(this.playlist.hasClass('empty')){
            this.playlist.find('li').remove()
            this.playlist.removeClass('empty')
          }

          this.playlist.append(this.playlistItemHTML(result))

          // Refresh collections that need to account for the new item.
          this.playlistItems = this.widget.find('.ar-playlist > ol > li')
          this.playlistPlayPause = this.playlistItems.find('.ar-playlist-play-pause')
          this.playlistDownload = this.playlistItems.find('.ar-playlist-download')
          this.playlistDelete = this.playlistItems.find('.ar-playlist-delete')

          this.setupPlaylistPlayPause()
          this.setupPlaylistDelete()
        }

        //console.log('Result:', result)
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
      formattedText = this.formatTime(this.recordingTime)
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

  this.formatTime = (seconds) => {
    seconds = Math.floor(seconds)
    var hours = Math.floor(seconds / 3600)
    seconds = seconds - (hours * 3600)
    var minutes = Math.floor(seconds / 60)
    seconds = seconds - (minutes * 60)
    var formattedText = minutes.toString().padStart(2, '0') + ':' + seconds.toString().padStart(2, '0')
    if(hours){
      formattedText = hours + ':' + formattedText;
    }
    return formattedText
  }

  this.warnRecordingSaveError = () => {
    alert('There was an error saving your recording to the server.  Please refresh this page and try again.')
  }

  this.warnUploadRecordingError = () => {
    alert('There was an error uploading your file to the server.  Please refresh this page and try uploading the file again.')
  }

  this.deleteRecording = (id) => {
    if (confirm('Are you sure you want to delete the recording?') == true) {
      var itemToDelete = this.playlistItems.filter('[data-id='+id+']')
      $.ajax({
        url: '/organizer/recording/delete/' + id,
        method: 'DELETE',
        beforeSend: (jqXHR, settings) => {
          jqXHR.setRequestHeader('X-CSRF-TOKEN', $('meta[name="_token"]').attr('content'))
          this.widget.addClass('deleting')
          itemToDelete.addClass('deleting')
        }
      }).done(
        (result, textStatus, jqXHR) => {
          itemToDelete.animate({"height": 0}, 250, 'swing', () => {
            itemToDelete.remove()
          })
          this.existingRecordingCount--
          this.widget.data('count', this.existingRecordingCount)
          this.widget.attr('data-count', this.existingRecordingCount)
          this.widget.removeClass('deleting')
          this.existingLabel.text(this.existingRecordingCount + ' ' + (this.existingRecordingCount == 1 ? 'Recording' : 'Recordings') + ' on File')
        }
      )
    }
  }
}


$(document).ready(function () {
  // Initialize all audio recorder widgets.
  $('.audio-recorder').each(function(i, element){
    window.audioRecorers.push(new AudioRecorder(element))
  })

  // Initialize the Dropzone.
  if($('#myAwesomeDropzone').lenth){
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
  }
})


niceDate = (dateString) => {
  const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
  var date = new Date(dateString)
  var month = months[date.getMonth()]
  var day = date.getDate()
  var year = date.getFullYear()
  var hour = date.getHours()
  var ampm = hour > 11 ? 'PM' : 'AM'
  if(hour == 0){
    hour = 12
  } else if(hour > 12){
    hour = hour - 12
  }
  hour = hour.toString().padStart(2, '0')
  var minute = date.getMinutes().toString().padStart(2, '0')
  var second = date.getSeconds().toString().padStart(2, '0')
  var timezone = 'UTC'

  return month + '. ' + day + ', ' + year + ' at ' + hour + ':' + minute + ':' + second + ' ' + ampm + ' (' + timezone + ')'
}

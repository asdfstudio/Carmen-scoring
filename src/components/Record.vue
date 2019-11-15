<template>
  <div>
    <button
      :disabled="choir.isDisabled"
      class="button"
      :class="{ 'cancel': choir.isDisabled }"
      v-on:click.stop.prevent="toggleRecording()"
    >
      <span v-show="!choir.isRecording">Start Recording</span>
      <span v-show="choir.isRecording">Stop Recording</span>
    </button>
    <ul ref="recList" class="list-container">
      <template v-for="(recording, index) in filteredRecordings">
      <li class="record-row" :key="index" >
        <span class="record-span">{{ index + 1 }}.</span>
        <div class="record-item-audio">
          <audio controls class="record-item">
            <source :src="recording.url">
          </audio>
           <span>{{recording.created_at}} (UTC)</span>
        </div>
      </li>
      </template>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'Record',
  props: {
    choir: {
      required: true
    },
    recordings: {
      required: false
    }
  },
  data () {
    return {
      unsavedRecordings: []
    }
  },
  computed: {
    filteredRecordings () {
      const allRecordings = [...this.recordings, ...this.unsavedRecordings]
      return allRecordings.filter(recording => recording.choir_id === this.choir.id)
    }
  },
  methods: {
    toggleRecording () {
      const that = this
      // start recording
      if (!this.choir.isRecording) {
        console.log('starting...', this.choir.id)
        this.$emit('start-recording')
        navigator.mediaDevices
          .getUserMedia({
            audio: true,
            video: false
          })
          .then(function (stream) {
            // shim for AudioContext when it's not avb.
            /* use the stream */
            that.stream = stream
            that.audioRecorder = new MediaRecorder(stream, {
              audioBitsPerSecond: 96000
            })
            that.audioRecorder.start()
            console.log('Media recorder started')
          })
          .catch(function () {
            /* handle the error */
            alert('Please plugin your earphone')
          })
      } else {
        // stop recording
        console.log('stopping...', this.choir.id)
        this.$emit('stop-recording')
        this.audioRecorder.stop()
        this.audioRecorder.ondataavailable = function (event) {
          that.recordingData = []
          that.recordingData.push(event.data)
        }
        this.audioRecorder.onstop = function (event) {
          console.log('Media recorder stopped')
          // stop microphone access
          that.stream.getAudioTracks()[0].stop()
          const blob = new Blob(that.recordingData, { type: 'audio/wav' })
          that.createDownloadLink(blob, this.choir)
        }
      }
    },
    createDownloadLink (blob, choir) {
      const URL = window.URL || window.webkitURL
      var currentdate = new Date()
      var datetime = currentdate.getUTCFullYear() +
       '-' + (currentdate.getUTCMonth() + 1) +
       '-' + currentdate.getUTCDate() +
       ' ' + currentdate.getUTCHours() +
       ':' + currentdate.getUTCMinutes() +
       ':' + currentdate.getUTCSeconds()
      var url = URL.createObjectURL(blob)
      this.unsavedRecordings.push({
        choir_id: this.choir.id,
        url: url,
        created_at: datetime
      })
      // upload link
      let formData = new FormData()
      formData.append('division_id', this.choir.division_id)
      formData.append('round_id', this.choir.round_id)
      formData.append('file', blob)
      formData.append('choir_id', this.choir.id)
      this.$store.dispatch('saveRecording', formData)
    }
  }
}
</script>

<style lang="scss" scoped>
button,
.button {
  background: #7f4091;
  color: #fff;
  padding: 10px 15px;
  margin: 0 5px;
  text-align: center;
  border: none;
  border-radius: 5px;

  &.cancel {
    background-color: #cccccc;
    color: #666666;
    padding: 9px 14px;
  }
}

.list-container {
  padding: 0px;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.record-row {
  list-style: none;
  display: flex;
  align-items: center;

  .record-span {
    margin-bottom: 23px;
  }
}

.record-item-audio {
  align-items: center; display:flex; flex-direction: column;
}

.record-item {
  padding: 10px;
}
</style>

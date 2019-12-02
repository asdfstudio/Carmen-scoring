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
        <li class="record-row" :key="index">
          <span class="record-span">{{ index + 1 }}.</span>
          <div class="record-item-audio">
            <audio controls class="record-item">
              <source :src="recording.url" controls="true" />
            </audio>
            <span>{{recording.created_at}}.mp3 (UTC)</span>
            <progress v-if="recording.isUnsaved" max="100" :value="uploadPercentage">
              <div class="progress-bar">
                <span :style="{ 'width': `${uploadPercentage}%;`}">Progress: {{ uploadPercentage }}%</span>
              </div>
            </progress>
          </div>
        </li>
      </template>
    </ul>
  </div>
</template>

<script>
import axios from 'axios'
const recorder = new MicRecorder({
  bitRate: 128
})

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
      unsavedRecordings: [],
      uploadPercentage: 0
    }
  },
  computed: {
    filteredRecordings () {
      const allRecordings = [...this.recordings, ...this.unsavedRecordings]
      return allRecordings.filter(
        recording => recording.choir_id === this.choir.id
      )
    }
  },
  methods: {
    toggleRecording () {
      // start recording
      if (!this.choir.isRecording) {
        console.log('starting...', this.choir.id)
        recorder.start()
          .then(() => {
            this.$emit('start-recording')
            // something else
          })
          .catch(e => {
            alert('Please plugin your microphone')
            return false
          })
      } else {
        // stop recording
        console.log('stopping...', this.choir.id)
        this.$emit('stop-recording')

        recorder.stop()
          .getMp3()
          .then(([buffer, blob]) => {
            const file = new File(buffer, 'music.mp3', {
              type: blob.type,
              lastModified: Date.now()
            })
            const URL = window.URL || window.webkitURL
            var url = URL.createObjectURL(blob)

            var currentdate = new Date()
            var datetime = currentdate.getUTCFullYear() +
            '-' + (currentdate.getUTCMonth() + 1) +
            '-' + currentdate.getUTCDate() +
            ' ' + currentdate.getUTCHours() +
            ':' + currentdate.getUTCMinutes() +
            ':' + currentdate.getUTCSeconds()
            this.unsavedRecordings.push({
              choir_id: this.choir.id,
              url: url,
              created_at: datetime,
              isUnsaved: true
            })

            let formData = new FormData()
            formData.append('division_id', this.choir.division_id)
            formData.append('round_id', this.choir.round_id)
            formData.append('file', file)
            formData.append('file_name', file)
            formData.append('choir_id', this.choir.id)
            this.uploadFile(formData)
          })
          .catch(e => {
            console.error(e)
          })
      }
    },
    uploadFile (payload) {
      let that = this
      that.$emit('upload-start')
      let config = {
        onUploadProgress: function (progressEvent) {
          var percentCompleted = Math.round(
            (progressEvent.loaded * 100) / progressEvent.total
          )
          that.$set(that.$data, 'uploadPercentage', percentCompleted)
        }
      }
      axios
        .post('/judge/recording/save', payload, config)
        .then(response => {})
        .finally(function () {
          that.$emit('upload-complete')
        })
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
  cursor: pointer;
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
  align-items: center;
  display: flex;
  flex-direction: column;
}

.record-item {
  padding: 10px;
}

.progress-bar {
  background-color: whiteSmoke;
  border-radius: 2px;
  box-shadow: 0 2px 3px rgba(0, 0, 0, 0.25) inset;

  width: 250px;
  height: 20px;

  position: relative;
  display: block;
}
.progress-bar > span {
  background-color: #7f4091;
  border-radius: 2px;

  display: block;
  text-indent: -9999px;
}
</style>

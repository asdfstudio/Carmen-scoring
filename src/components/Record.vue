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
            <span><a :href="recording.url" :download="recording.created_at + '.mp3'">{{recording.created_at}}.mp3 (UTC)</a></span>
            <div v-if="recording.isUnsaved && isUploading" class="slider">
              <div class="line"></div>
              <div class="subline inc"></div>
              <div class="subline dec"></div>
            </div>
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
      isUploading: false
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
        recorder.start()
          .then(() => {
            this.$emit('start-recording')
            // something else
          }).catch(e => {
            console.log(e)
            if(e.name === 'NotFoundError')
            {
               alert('Please plugin your microphone')
            }else if(e.name === 'TypeError')
            {
               alert('Your browser does not support recording')
            }else{
               alert('Something went wrong')
            }
            return false
          })
      } else {
        // stop recording
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
            this.uploadFile(formData, this.unsavedRecordings.length - 1)
          })
          .catch(e => {
            console.error(e)
          })
      }
    },
    uploadFile (payload, index) {
      console.log(payload)
      this.isUploading = true
      axios
        .post('/judge/recording/save', payload)
        .then(response => {
            console.log('response', response)
            if(typeof response.statusText !== 'undefined' && response.statusText === 'Created'){
              this.unsavedRecordings[index].isUnsaved = false
            } else {
              // In case of error.
              this.$emit('upload-error')
              this.unsavedRecordings[index].isUnsaved = true
            }
          },
          response => {
            // In case of error.
            console.log('response', response)
            this.$emit('upload-error')
            this.unsavedRecordings[index].isUnsaved = true
          }
        )
        .finally(() => {
          this.$emit('upload-complete')
          this.isUploading = false
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

.slider{
  position: relative;
  width: 200px;
  height: 5px;
  padding: 5px 0px 10px 0px;
  overflow-x: hidden;
}

.line{
  position: absolute;
  opacity: 0.4;
  background: #7f4091;
  width: 150%;
  height: 5px;
}

.subline{
  position: absolute;
  background: #7f4091;
  height: 5px;
}
.inc{
animation: increase 2s infinite;
}
.dec{
animation: decrease 2s 0.5s infinite;
}

@keyframes increase {
 from { left: -5%; width: 5%; }
 to { left: 130%; width: 100%;}
}
@keyframes decrease {
 from { left: -80%; width: 80%; }
 to { left: 110%; width: 10%;}
}

</style>

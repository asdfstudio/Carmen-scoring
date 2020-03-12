<template>
  <div
    class="audio-recorder"
    :id="'audio-recorder-' + choir.id"
    data-mode="recorder"
    data-role="judge"
    :data-count="filteredRecordings.length"
    :data-choir="choir.id"
    :data-round="choir.round_id"
    :data-division="choir.division_id"
  >
    <div class="ar-control">
      <button>
        <span class="ar-control-symbol"></span>
      </button>
    </div>
    <div class="ar-info">
      <div class="ar-title">Audio Recorder</div>
      <div class="ar-title-playback">Playing: <span></span></div>
      <div class="ar-existing">{{ filteredRecordings.length }} {{ filteredRecordings.length == 1 ? 'Recording' : 'Recordings' }} on File</div>
    </div>
    <div class="ar-playback-close"></div>
    <div class="ar-progress">
      <div class="ar-meter-box">
        <div class="ar-meter-bar"></div>
      </div>
      <div class="ar-progress-text">--:--</div>
    </div>
    <div class="ar-playlist" :data-recordings="JSON.stringify(filteredRecordings)"></div>
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
  computed: {
    filteredRecordings () {
      return this.recordings.filter(
        recording => recording.choir_id === this.choir.id
      )
    }
  }
}
</script>

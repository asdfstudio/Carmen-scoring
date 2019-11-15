<template>
  <vue2-dropzone :options="dropzoneOptions" @vdropzone-sending="uploadFile" />
</template>

<script>
import vue2Dropzone from 'vue2-dropzone'
import 'vue2-dropzone/dist/vue2Dropzone.min.css'

export default {
  name: 'DropZone',
  components: { vue2Dropzone },
  props: {
    choir: {
      required: true
    }
  },
  data: function () {
    return {
      dropzoneOptions: {
        url: window.origin + '/judge/recording/save',
        acceptedFiles: 'audio/*',
        addRemoveLinks: true,
        maxFilesize: 20,
        headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="_token"]').getAttribute('content')}
      }
    }
  },
  methods: {
    uploadFile: function (file, xhr, formData) {
      formData.append('division_id', this.choir.division_id)
      formData.append('round_id', this.choir.round_id)
      formData.append('choir_id', this.choir.id)
      const URL = window.URL || window.webkitURL
      formData.append('url', URL.createObjectURL(file))
      this.$store.dispatch('saveRecording', formData)
    }
  }
}
</script>

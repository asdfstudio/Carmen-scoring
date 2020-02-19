<template>
  <vue2-dropzone ref="dropzone" :options="dropzoneOptions" @vdropzone-sending="uploadFile"  @vdropzone-file-added="uploadprogress" @vdropzone-success="success" @vdropzone-complete="complete" @vdropzone-error="error" />
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
        maxFilesize: 500,
        headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="_token"]').getAttribute('content')}
      }
    }
  },
  methods: {
    uploadprogress: function (file) {
      this.$emit('upload-start')
    },
    uploadFile: function (file, xhr, formData) {
      formData.append('division_id', this.choir.division_id)
      formData.append('round_id', this.choir.round_id)
      formData.append('choir_id', this.choir.id)
    },
    success: function (file, response) {
      if(typeof response === 'undefined' || typeof response.url === 'undefined'){
        console.log(response)
        this.$emit('upload-error')
      }
    },
    complete: function (file, response) {
      this.$emit('upload-complete')
    },
    error: function (file, message, xhr) {
      console.log(message)
      console.log(xhr)
      this.$emit('upload-error')
    }
  },
  beforeDestroy () {
    this.$refs.dropzone.removeAllFiles(true)
  }
}
</script>

<template>
  <div id="modal-body">
    <slot></slot>
  </div>
</template>

<script>
export default {
  name: 'ModalBody'
}

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
#modal-body {
  padding: 25px;
  background: #fff;
  margin: 0;
  overflow-y: scroll;
  max-height: 78vh;
  border-bottom-left-radius: 8px;
  border-bottom-right-radius: 8px;
}
</style>

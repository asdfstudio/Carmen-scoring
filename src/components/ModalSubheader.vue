<template>
  <div id="modal-subheader">
    <slot></slot>
  </div>
</template>

<script>
export default {
  name: 'ModalSubheader'
}

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
#modal-subheader {
  padding: 10px;
  min-height: 30px;
  background: #F8F7F7;
  color: #ABABAB;
  border-top-left-radius: 8px;
  border-top-right-radius: 8px;
}

p {
  margin: 0
}
</style>

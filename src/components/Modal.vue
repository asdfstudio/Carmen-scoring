<template>
  <div id="modal" @click.stop>
    <slot></slot>
  </div>
</template>

<script>

export default {
  name: 'Modal'
}

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
#modal {
  max-width: 100%;
  background: #F0F0F0;
  padding: 10px;
  margin: 20px auto;
  overflow: hidden;
  border-radius: 16px;
  z-index: 100;
  position: absolute;
  top: 20px;
  left: 0;
  right: 0;
}

@media (min-width: 750px) {
  #modal {
    width: 750px;
  }
}
</style>

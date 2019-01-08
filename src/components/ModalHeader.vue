<template>
  <div id="modal-header">
    <!--<button @click="deactivateModal" type="button" name="button" class="cancel-button">Cancel</button>-->
    <slot></slot>
    <!--<button @click="deactivateModal" type="button" name="button" class="save-button">Save</button>-->
  </div>
</template>

<script>
export default {
  name: 'ModalHeader',
  methods: {
    deactivateModal: function () {
      this.$store.commit('deactivateModal')
    }
  }
}

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
#modal-header {
  padding: 20px 0 3px;
  min-height: 30px;
  color: #707070
}
button {
  background: #ccc;
  padding: 5px 15px;
  text-transform: lowercase;
  border-radius: 20px;
  border: 0;
  position: absolute;
  top: 15px;
}

.cancel-button {
  left: 15px;
}

.save-button {
  right: 15px;
}

h1, h2, h3 {
  margin-top: 0;
  margin-bottom: 10px;
  font-weight: normal;
}

h1 {
  font-size: 28px;
  line-height: 28px;
}

h2 {
  font-size: 20px;
  line-height: 20px;
}

h3 {
  font-size: 18px;
  line-height: 18px;
}
</style>

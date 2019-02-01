<template>
  <div>
    <div v-on:click="incrementCount" class="current-count">
      {{ count }}
    </div>

    <input v-on:change="changeCount" />
  </div>
</template>

<script>

export default {
  name: 'Counter',
  data: function () {
    return {
      stateCount: 0
    }
  },
  computed: {
    count () {
      return this.$store.state.count
    }
  },
  methods: {
    incrementCount: function () {
      console.log('+')
      this.stateCount++
      this.$store.commit('increment')
    },
    changeCount: function (event) {
      console.log(event.target.value)
      this.$store.commit('setCount', event.target.value)
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
div {
  border: 1px solid;
  padding: 10px;
  margin: 20px;
}
</style>

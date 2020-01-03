<template>
  <option class="score-option" v-bind:value="score">{{ score }}</option>
</template>

<script>
export default {
  name: 'ScoreOption',
  props: {
    score: Number,
    currentScore: Number
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
</style>

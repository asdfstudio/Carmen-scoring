<template>
  <button class="score-button" v-bind:class="size" @click="$emit('score-changed')">{{ score }}</button>
</template>

<script>
export default {
  name: 'ScoreButton',
  props: {
    score: Number,
    currentScore: Number,
    size: {
      type: String,
      default: 'large'
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
.score-button {
  border-radius: 8px;
  background: #F0F0F0;
  margin: 5px;
  border: none;
  width: 40px;
  height: 40px;
  line-height: 40px;
  font-size: 20px;
  color: #707070;

  &.active {
    background: #56A797;
    color: #fff;
  }

  &.small {
    width: 28px;
    height: 28px;
    line-height: 28px;
    font-size: 15px;
  }
}
</style>

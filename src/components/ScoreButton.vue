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
  padding: 0;
  border: none;
  width: 40px;
  height: 40px;
  line-height: 40px;
  font-size: 20px;
  color: #707070;

  &.active {
    background: #7F4091;
    color: #fff;
  }

  &.small {
    width: 28px;
    height: 28px;
    line-height: 28px;
    font-size: 15px;
  }

  .doubleRow &:nth-child(odd) {
    position: relative;
    bottom: 25px;
    margin-bottom: 25px;
    margin-left: -7px;
    margin-right: -7px;
  }

  .doubleRow &:nth-child(even) {
    position: relative;
    top: 25px;
    margin-top: 25px;
    margin-left: -7px;
    margin-right: -7px;
    font-size: 18px;
  }
}
</style>

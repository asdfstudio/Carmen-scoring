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

  .doubleRow & {
    margin-bottom: 25px;
    margin-left: -7px;
    margin-right: -7px;
  }

  .doubleRow &:nth-child(odd) {
    position: relative;
    bottom: 25px;
  }

  .doubleRow &:nth-child(even) {
    position: relative;
    top: 25px;
    font-size: 18px;
  }

  &.small,
  .doubleRow &.small {
    width: 26px;
    height: 26px;
    line-height: 26px;
    font-size: 14px;
    margin-bottom: 30px;
    margin-left: -5px;
    margin-right: -5px;
  }

  .doubleRow &.small:nth-child(odd) {
    bottom: 6px;
  }

  .doubleRow &.small:nth-child(even) {
    top: 27px;
  }

  @media (max-width: 639px) {
    & {
      display: none;
    }
  }
}
</style>

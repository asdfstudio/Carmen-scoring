
import Vue from 'vue'

export default Vue.component('single-score', {
  template: '<div><div class="current-score">{{ currentScore }}</div><button @click="down">Subtract {{ increment }}</button><button @click="up">Add {{ increment }}</button><div class="score-buttons"><button v-for="n in max" @click="change(n)">{{ n }}</button></div></div>',
  props: {
    min: Number,
    max: Number,
    increment: Number,
    current: Number
  },
  data: function () {
    return {
      currentScore: this.current
    }
  },
  methods: {
    down: function (event) {
      var newScore = this.currentScore - this.increment
      if (newScore >= this.min) {
        this.currentScore = newScore
      }
    },
    up: function (event) {
      var newScore = this.currentScore + this.increment
      if (newScore <= this.max) {
        this.currentScore = newScore
      }
    },
    change: function (newScore) {
      if (newScore >= this.min && newScore <= this.max) {
        this.currentScore = newScore
      }
    }
  }
})

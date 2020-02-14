<template>
  <div>
    <div class="score-container" v-bind:class="displayType">
      <div class="row" v-bind:class="displayType">
        <button @click="down" v-if="showIncrements  && isScoringActive" class="score-down score-increment" v-bind:class="{ outlined : isIncrementOutlined }">-</button>

        <div
          class="current-score"
          v-bind:class="{saving: getSavingStatus(choirId + '_' + captionId + '_' + criterionId), saved: getSavedStatus(choirId + '_' + captionId + '_' + criterionId), errored: getErroredStatus(choirId + '_' + captionId + '_' + criterionId)}"
        >{{ displayScore }}</div>

        <button @click="up" v-if="showIncrements  && isScoringActive" class="score-up score-increment" v-bind:class="{ outlined : isIncrementOutlined }">+</button>
      </div>

      <CriterionScoringRange v-if="showScoringRange" :min="min" :max="max" :increment="increment" />

      <div v-if="showScoreChoices && isScoringActive" class="score-buttons" v-bind:class="[displayType, { doubleRow: increment === .5 }]">
        <select class="score-select" v-model="currentScore">
          <option class="score-option" v-bind:value="initialScore">Select Score...</option>
          <ScoreOption
            v-for="n in range"
            v-bind:key="n"
            :score="n"
            :currentScore="currentScore"
            @score-changed="change(n)"
          />
        </select>
        <ScoreButton
          v-for="n in range"
          v-bind:key="n"
          v-bind:class="{ active: currentScore === n }"
          :score="n"
          :currentScore="currentScore"
          @score-changed="change(n)"
          :size="scoreButtonSize"
        />
      </div>
    </div>
  </div>
</template>

<script>
import CriterionScoringRange from './CriterionScoringRange'
import ScoreButton from './ScoreButton'
import ScoreOption from './ScoreOption'

export default {
  name: 'Score',
  components: {
    CriterionScoringRange,
    ScoreButton,
    ScoreOption
  },
  props: {
    min: Number,
    max: Number,
    increment: Number,
    initialScore: Number,
    isScoringActive: Boolean,
    showIncrements: Boolean,
    showScoreChoices: Boolean,
    showScoringRange: Boolean,
    incrementOutlined: Boolean,
    displayType: String,
    choirId: Number,
    criterionId: Number,
    captionId: Number,
    scoreButtonSize: {
      default: 'large'
    }
  },
  data: function () {
    return {
      currentScore: this.initialScore
    }
  },
  computed: {
    /* isScoringActive () {
      return this.$store.state.isScoringActive
    }, */
    range () {
      var range = []
      for (var i = this.min; i <= this.max; i = i + this.increment) {
        range.push(i)
      }
      return range
    },
    isScoreChanged () {
      return this.currentScore !== this.initialScore
    },
    isIncrementOutlined () {
      return this.incrementOutlined
    },
    displayScore () {
      return this.currentScore ? this.currentScore : '-'
    }
  },
  watch: {
    currentScore: function (newValue, oldValue) {
      const payload = {
        choir_id: this.choirId,
        criterion_id: this.criterionId,
        caption_id: this.captionId,
        raw_score: newValue
      }
      this.$store.dispatch('setScore', payload)
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
    },
    getSavingStatus: function (property) {
      return this.$store.getters.getSavingStatus(property)
    },
    getSavedStatus: function (property) {
      return this.$store.getters.getSavedStatus(property)
    },
    getErroredStatus: function (property) {
      return this.$store.getters.getErroredStatus(property)
    }
  }
}

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
.score-container {
  .row {
    overflow: hidden;
    text-align: center;
    width: 100%;
    margin: auto;
    display: block;

    &.inline {
      display: block;
      width: auto;
      margin: 15px 20px 5px;
    }

    button.score-increment {
      width: 40px;
      height: 40px;
      line-height: 40px;
      border-radius: 20px;
      background: #7F4091;
      color: white;
      border: none;
      display: inline-block;
      font-size: 33px;
      padding: 0;

      &.outlined {
        color: #7F4091;
        background: none;
        border: 1px solid #7F4091;
      }
    }

    .current-score {
      padding: 0 10px;
      font-size: 36px;
      margin: 0 10px;
      color: #7F4091;
      display: inline-block;
      width: 80px;
    }

    &.compact, &.inline {
      font-size: 14px;

      button.score-increment {
        width: 30px;
        height: 30px;
        line-height: 30px;
        font-size: 24px;

        @media (max-width: 359px) {
          & {
            width: 25px;
            height: 25px;
            line-height: 25px;
            font-size: 20px;
          }
        }
      }

      .current-score {
        font-size: 24px;
        width: 40px;
        color: #707070;

        @media (max-width: 359px) {
          & {
            position: relative;
            top: -2px;
            font-size: 20px;
            margin: 0;
            width: auto;
          }
        }

        &.saving::after {
          background: url(/images/loading-puff-purple.svg) center center no-repeat;
          background-size: contain;
        }

        &.saved::after {
          background: url(/images/check-solid-purple.svg) center center no-repeat; /* Icon by FontAwesome: https://fontawesome.com/license */
          background-size: contain;
        }

        &.errored::after {
          height: 20px;
          width: 20px;
          top: 8px;
          margin-left: 15px;
        }
      }
    }
  }

  .score-buttons {
    background: #fff;
    margin: 5px;
    padding: 10px 5px;
    border-radius: 8px;

    &.inline {
      display: block;
    }

    @media (min-width: 640px) {
      & {
        min-width: 340px;
      }
    }
  }

  .score-select {
    padding: 10px 20px;
    font-size: 16px;
    border: 1px solid #7f4091;
    color: #7f4091;

    @media (min-width: 640px) {
      & {
        display: none;
      }
    }

    @media (max-width: 359px) {
      & {
        padding: 8px;
        font-size: 12px;
      }
    }
  }

}

</style>

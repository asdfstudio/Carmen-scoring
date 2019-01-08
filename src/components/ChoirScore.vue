<template>
  <tr class="choir-score">
    <td class="choir-column">
      <div @click="activateScoring">
        {{ currentChoir.name }}
      </div>

      <button class="deactive-scoring-button" v-if="scoringActive" @click="deactivateScoring">Done scoring</button>

      <!--<button v-if="scoringActive" @click="deactivateScoring">Cancel</button>-->
    </td>
    <td @click="activateScoring" >
      <Score
        :min="currentCriterion.minScore"
        :max="currentCriterion.maxScore"
        :initialScore="currentScore"
        :increment="currentCriterion.increment"
        :showIncrements="true"
        :showScoreChoices="true"
        :showScoringRange="false"
        :isScoringActive="scoringActive"
        :displayType="displayType"
        :choirId="currentChoir.id"
        :criterionId="currentCriterion.id"
        :captionId="currentCriterion.caption_id"
        :scoreButtonSize="scoreButtonSize"
      ></Score>

      <!--<button v-if="scoringActive" @click="saveScoring">Save</button>-->
    </td>
  </tr>
</template>

<script>
import Score from './Score'

export default {
  name: 'ChoirScore',
  components: {
    Score
  },
  props: {
    choir: Object,
    criterion: Object,
    isScoringActive: Boolean,
    score: Number
  },
  methods: {
    activateScoring: function () {
      this.scoringActive = true
    },
    deactivateScoring: function () {
      this.scoringActive = false
    },
    toggleScoring: function () {
      this.scoringActive = !this.scoringActive
    },
    saveScoring: function () {
      // this.currentChoir.score = newScore
      this.scoringActive = false
    }
  },
  data: function () {
    return {
      // currentChoir: this.choir,
      currentCriterion: this.criterion,
      scoringActive: this.isScoringActive,
      displayType: 'compact',
      scoreButtonSize: 'small'
    }
  },
  computed: {
    currentChoir () {
      return this.choir
    },
    currentScore () {
      return this.score
    }
  }
}

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
table td {
  background: #F8F7F7;
}

.choir-column {
  padding-right: 20px;
  text-align: right;
}

.deactive-scoring-button {
  background: #fff;
  border: 1px solid #ccc;
  border-radius: 5px;
  color: #777;
  padding: 4px 8px;
  margin-top: 10px;
}
</style>

<template>
  <table class="criteria-scores">

    <tr class="caption-header" :class="['background-color-' + caption.color_id]">
      <th colspan="2" class="caption-name">{{ caption.name }}</th>
    </tr>

    <tr class="table-header">
      <th class="criterion-column">Criteria</th>
      <th class="score-column">Score</th>
    </tr>
    <CriterionScore
      v-for="criterion in criteriaList" v-bind:key="criterion.id"
      :choir="choir"
      :criterion="criterion"
      :score="score(criterion.id)"
      :isScoringActive="true"
    ></CriterionScore>

    <!-- Total score start -->
    <tr class="caption-row caption-footer">
      <td class="caption-subtotal caption-subtotal-label" :class="['lighter-background-color-' + caption.color_id]">
        Subtotal
      </td>
      <td class="caption-subtotal caption-subtotal-value"
      :class="['lighter-background-color-' + caption.color_id]">
        {{ getChoirCaptionSubtotalScore(choir, caption) }}
      </td>
    </tr>
    <!-- Total score end -->
  </table>
</template>

<script>
import CriterionScore from './CriterionScore'

export default {
  name: 'CriteriaScores',
  components: {
    CriterionScore
  },
  props: {
    criteriaList: Array,
    choir: Object,
    scores: Array,
    caption: Object
  },
  methods: {
    score (criterionId) {
      var matches = this.scores.filter(score => score.criterion_id === criterionId)

      if (matches.length === 1) return matches[0].raw_score

      return null
    },
    choirTotalScore: function (choir) {
      return this.$store.getters.getChoirTotalScore(choir.id)
    },
    getChoirCaptionSubtotalScore: function (choir, caption) {
      return this.$store.getters.getChoirCaptionSubtotalScore(choir.id, caption.id)
    }
  }
}

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
table {
  width: 100%;
  max-width: 700px;
  position: relative;
  margin: auto;
}

.caption-name {
  color: #fff;
  text-align: center;
  padding: 7px;
  font-weight: normal;
}

.table-header {
  background: #aaa;
  color: #fff;
  padding: 10px;

  th {
    font-size: .9em;
    font-weight: normal;

  }
}

.criterion-column {
  padding-right: 20px;
  text-align: right;
  width: 150px;
}

th.score-column {
  text-align: left;
  padding-left: 20px;
}

.score-column {
  width: 200px;
}

tbody tr.caption-header {

  & td.caption-name {
    color: white;
    text-align: left;
    padding: 5px 10px;
    font-size: 1.2em;
  }
}

tr.caption-footer {
  & td.caption-subtotal {
    color: white;
    padding: 5px;

    &.caption-subtotal-label {
      text-align: right;
      padding-right: 10px;
    }

    &.caption-subtotal-value {
      text-align: center;
    }
  }
}
</style>

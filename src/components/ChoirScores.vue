<template>
  <table class="choirs-scores">
    <tr class="table-header">
      <th class="choir-column">Choir name</th>
      <th class="score-column">Score</th>
    </tr>
    <ChoirScore
      v-for="choir in choirsList" v-bind:key="choir.id"
      :choir="choir"
      :criterion="criterion"
      :score="score(choir.id)"
    ></ChoirScore>
  </table>
</template>

<script>
import Score from './Score'
import ChoirScore from './ChoirScore'

export default {
  name: 'ChoirScores',
  components: {
    Score,
    ChoirScore
  },
  props: {
    choirsList: Array,
    criterion: Object,
    scores: Array
  },
  methods: {
    score (choirId) {
      var matches = this.scores.filter(score => score.choir_id === choirId)

      if (matches.length === 1) return matches[0].raw_score

      return null
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

.table-header {
  background: #7F4091;
  color: #fff;
  padding: 10px;
}

.choir-column {
  padding-right: 20px;
  text-align: right;
}

.score-column {
  max-width: 200px;
}
</style>

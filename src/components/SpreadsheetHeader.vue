<template>
  <div id="spreadsheet-header">
    <a class="button back-button" :href="backUrl">Back to previous screen</a>
    <h2> {{ spreadsheetTitle}}
      <div class="scoring-status" v-if="isSpreadsheetScoringActive">
        Scoring is active
      </div>
      <div class="scoring-status" v-if="!isSpreadsheetScoringActive">
        Scores can't be edited now.
      </div>
    </h2>
  </div>
</template>

<script>
export default {
  name: 'SpreadsheetHeader',
  computed: {
    scoringStatus () {
      return this.$store.state.scoringStatus
    },
    isSpreadsheetScoringActive () {
      return this.$store.state.isSpreadsheetScoringActive
    },
    spreadsheetTitle () {
      return this.$store.state.spreadsheetTitle
    },
    backUrl () {
      return this.$store.state.backUrl
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
#spreadsheet-header {
  padding: 15px 0;
  min-height: 30px;
  width: 100%;
  color: #fff;
  position: relative;
  top: 0;
  left: 0;
  right: 0;
  background: #272B2D;
  text-align: left;
  overflow: scroll;

  h2 {
    margin-top: 0;
    margin-left: 240px;
  }
}
button, .button {
  padding: 3px 15px;
  text-transform: lowercase;
  border: 1px solid;
  border-bottom-width: 2px;
  position: absolute;
  top: 12px;
  color: #fff;
  text-decoration: none;
  font-size: 14px;

  &:hover, &:active, &.active {
    background: #fff;
    color: #333;
  }
}

.back-button {
  position: relative;
  top: 5px;
  left: 15px;
}

@media (max-width: 600px) {
  #spreadsheet-header h2 {
    margin-top: 20px;
    margin-left: 15px;
  }
}

.change-division-button {
  right: 15px;
}

.scoring-status {
  display: inline;
  font-size: 14px;
  margin-left: 10px;
}

h1, h2, h3 {
  margin-top: 0;
  margin-bottom: 10px;
  font-weight: normal;
}

h1 {
  font-size: 28px;
  line-height: 28px;
}

h2 {
  font-size: 20px;
  line-height: 20px;
}

h3 {
  font-size: 18px;
  line-height: 18px;
}
</style>

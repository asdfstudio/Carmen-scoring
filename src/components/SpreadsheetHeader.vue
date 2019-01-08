<template>
  <div id="spreadsheet-header">
    <button type="button" name="button" class="back-button">Back to Overview</button>
    <h2> Division name here
      <div class="scoring-status">
        Scoring is {{ scoringStatus }}
      </div>
    </h2>

    <button type="button" name="button" class="change-division-button">Change Division</button>
  </div>
</template>

<script>
export default {
  name: 'SpreadsheetHeader',
  computed: {
    scoringStatus () {
      return this.$store.state.scoringStatus
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
#spreadsheet-header {
  padding: 15px 0 3px;
  min-height: 30px;
  color: #fff;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  background: #484848;
  margin-bottom: 20px;
}
button {
  background: #ccc;
  padding: 5px 15px;
  text-transform: lowercase;
  border-radius: 20px;
  border: 0;
  position: absolute;
  top: 12px;
}

.back-button {
  left: 15px;
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

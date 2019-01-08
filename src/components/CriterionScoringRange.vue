<template>
  <div class="valid-scores-summary" v-bind:class="displayType">
    <div class="expanded-version" v-if="displayType === 'expanded'">
      <span class="label">Min:</span> {{ min }},
      <span class="label">Max:</span> {{ max }},
      <span class="label">Increment:</span> {{ increment }}
    </div>

    <div class="condensed-version" v-if="displayType === 'condensed'">
      {{ min }} - {{ max }} @ {{ increment }}
    </div>

  </div>
</template>

<script>
export default {
  name: 'CriterionScoringRange',
  props: {
    min: Number,
    max: Number,
    increment: Number,
    displayType: String
  }
}

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
.valid-scores-summary {
  color: #C3C2C2;
  font-size: 18px;
  line-height: 21px;
  margin: 20px 0;

  &.condensed {
    display: inline;
    font-size: 14px;
    line-height: 19px;
  }
}

.label {
  font-size: 0.8em;
}
</style>

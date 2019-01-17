<template>
  <div id="spreadsheet">
    <table>
      <thead>
        <tr class="table-header">
          <th class="criteria-header">
            <!--Caption / Criteria-->
          </th>
          <th v-for="choir in choirsList"  :choir="choir" v-bind:key="choir.id">
            <span class="clickable" @click="activateChoirModal(choir)">{{ choir.name }}</span>
          </th>
        </tr>
      </thead>
      <tbody>

        <!-- Caption container start -->
        <template v-for="caption in captionsList">

        <!-- Caption header start -->
        <tr class="caption-row caption-header" v-bind:key="caption.id">
          <th class="caption-name" :class="['background-color-' + caption.color_id]">
            {{ caption.name }}
          </th>
          <td :colspan="choirsList.length" :class="['background-color-' + caption.color_id]">

          </td>
        </tr>
        <!-- Caption header end -->

        <!-- Caption Criteria Start -->
        <tr class="criteria-row" v-for="criterion in criteriaList.filter(cr => cr.caption_id === caption.id)" v-bind:key="criterion.id">
          <th  class="criterion-name">
            <span class="clickable" @click="activateCriterionModal(criterion)">{{ criterion.name }}</span>
          </th>

          <td
            v-for="choir in choirsList"
            @click="activateChoirCriterionModal(choir, criterion)"
            :choir="choir"
            :criterion="criterion"
            v-bind:key="choir.id"
            >{{ score(choir, criterion) }}</td>
        </tr>
        <!-- Caption Criteria End -->

        <!-- Caption footer start -->
        <tr class="caption-row caption-footer" v-bind:key="caption.id">
          <th class="caption-subtotal caption-subtotal-label" :class="['lighter-background-color-' + caption.color_id]">
            Subtotal
          </th>
          <td
            v-for="choir in choirsList"
            :choir="choir"
            v-bind:key="choir.id"
            class="caption-subtotal caption-subtotal-value"
            :class="['lighter-background-color-' + caption.color_id]"
            >
              {{ getChoirCaptionSubtotalScore(choir, caption) }}
            </td>
        </tr>
        <!-- Caption footer end -->

        </template>
        <!-- Caption container end -->

        <!-- Total score start -->
        <tr class="score-row">
          <th class="score-total-label">
            Total
          </th>
          <td
            v-for="choir in choirsList"
            :choir="choir"
            v-bind:key="choir.id"
            class="score-total-value"
            >
            {{ choirTotalScore(choir) }}
          </td>
        </tr>
        <!-- Total score end -->

        <!-- Comments -->
        <tr class="comment-row">
          <th class="criterion-name">Comments</th>

          <td
            v-for="choir in choirsList"
            @click="activateChoirCommentModal(choir)"
            :choir="choir"

            v-bind:key="choir.id"
            >{{ comment(choir) }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>

export default {
  name: 'Spreadsheet',
  data: function () {
    return {
      activeChoir: null,
      activeCriterion: null
    }
  },
  computed: {
    choirsList () {
      return this.$store.state.choirsList
    },
    captionsList () {
      return this.$store.state.captionsList
    },
    criteriaList () {
      return this.$store.state.criteriaList
    },
    scores () {
      return this.$store.state.scores
    },
    activeModal () {
      return this.$store.state.activeModal
    },
    isSpreadsheetScoringActive () {
      return this.$store.state.isSpreadsheetScoringActive
    }
  },
  watch: {
    activeChoir: function (newValue, oldValue) {
      if (this.activeChoir) {
        // const data = {'type': 'choir', 'resource': this.activeChoir}
        // this.$store.commit('activateModal', data)
      } else {
        // this.$store.commit('deactivateModal')
      }

      // this.$store.commit('setActiveModal', true)
    }
  },
  methods: {
    displayScoringInactiveMessage: function () {
      alert('Scoring is currently inactive.')
    },
    activateModal: function (data) {
      this.$store.commit('activateModal', data)
    },
    activateChoirModal: function (choir) {
      if (this.isSpreadsheetScoringActive) {
        this.$store.commit('activateChoirModal', choir)
      } else {
        this.displayScoringInactiveMessage()
      }
    },
    activateChoirCommentModal: function (choir) {
      if (this.isSpreadsheetScoringActive) {
        this.$store.commit('activateChoirCommentModal', choir)
      } else {
        this.displayScoringInactiveMessage()
      }
    },
    activateCriterionModal: function (criterion) {
      if (this.isSpreadsheetScoringActive) {
        this.$store.commit('activateCriterionModal', criterion)
      } else {
        this.displayScoringInactiveMessage()
      }
    },
    activateChoirCriterionModal: function (choir, criterion) {
      if (this.isSpreadsheetScoringActive) {
        this.$store.commit('activateChoirCriterionModal', {choir, criterion})
      } else {
        this.displayScoringInactiveMessage()
      }
    },
    activateCriterion: function (criterion) {
      // criterion.scores = this.$store.getters.getCriterionScores(criterion.id)
      this.activeCriterion = criterion
      this.$store.commit('activateCriterion', this.activeCriterion)
    },
    deactiveCriterion: function () {
      this.activeCriterion = null
    },
    activateChoir: function (choir) {
      this.activeChoir = choir
      this.$store.commit('activateChoir', this.activeChoir)
    },
    activateChoirCriterion: function (choir, criterion) {
      this.activeChoir = choir
      this.activeCriterion = criterion
      this.$store.commit('activateChoir', this.activeChoir)
      this.$store.commit('activateCriterion', this.activeCriterion)
    },
    deactiveChoir: function () {
      this.activeChoir = null
    },
    incrementCount: function () {
      this.$store.commit('increment')
    },
    setCount: function (newCount) {
      this.$store.commit('setCount', newCount)
    },
    score: function (choir, criterion) {
      return this.$store.getters.getChoirCriterionScore(choir.id, criterion.id)
    },
    choirTotalScore: function (choir) {
      return this.$store.getters.getChoirTotalScore(choir.id)
    },
    getChoirCaptionSubtotalScore: function (choir, caption) {
      return this.$store.getters.getChoirCaptionSubtotalScore(choir.id, caption.id)
    },
    comment: function (choir) {
      return this.$store.getters.getChoirComment(choir.id)
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
#spreadsheet {
  margin: 5px;
  margin-top:50px;
  position: relative;
  width: 100%;
  z-index: 1;
  overflow: scroll;
  height: 700px;

  &.fixed {
    position: fixed;
  }

  th, td {
    padding: 5px 10px;
    border: 1px solid #ddd;
    background: #fff;
    vertical-align: top;
    font-weight: normal;
  }

  th {
    background: #f9f9f9;
  }

  th:first-child {
    position: -webkit-sticky;
    position: sticky;
    left: 0;
    z-index: 2;
    background: #f9f9f9;
    width: 200px;
    border-right-width: 3px;
  }

  thead th:first-child {
    z-index: 5;
  }

  tbody th {
    text-align: right;
  }
}

table {
  width: 100%;
  min-width: 1280px;
  margin: auto;
  border-collapse: separate;
  border-spacing: 0;
  color: #333;
  font-size: 14px;

  thead th {
    position: -webkit-sticky;
    position: sticky;
    top: 0;
    background: #eee;
    padding: 10px 5px;
    color: #333333;
  }

  tr.table-header {

    color: #fff;
    font-size: .9em;

    td {
      padding: 10px;
      color: #444;
      border-left: 1px solid #ccc;

      &.criteria-header {
        background: none;
        border:none;
      }
    }
  }

  tbody tr.caption-row {
    &.caption-header {

      & .caption-name {
        color: white;
        text-align: left;
        padding: 5px 10px;
        font-size: 1.2em;
        font-weight: normal;
        position: sticky;
      }
    }

    &.caption-footer {
      & td.caption-subtotal, th.caption-subtotal {
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
  }

  tbody td {
    background: #F8F7F7;
    padding: 4px;

    &.criterion-name {
      font-size: 1.1em;
      text-align: right;
      padding: 5px 15px;
      width: 150px;
    }
  }

  tr.score-row {
    font-weight: bold;
    font-size: 1.1em;

    td {
      border-top: 4px solid #ccc;

      &.score-total-label {
        text-align: right;
        padding: 8px;
        padding-right: 10px;
      }
      &.score-total-value {
        padding: 8px
      }
    }

  }

  tr.comment-row {
    font-size: 13px;
  }
}

</style>

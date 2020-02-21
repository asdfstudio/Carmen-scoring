<template>
  <div id="spreadsheet">
    <table>
      <thead>
        <tr class="table-header">
          <th class="criteria-header">
            <!--Caption / Criteria-->
          </th>

          <th v-for="choir in choirsList" class="choir-header"  :choir="choir" v-bind:key="choir.id">
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
            class="caption-value"
            @click="activateChoirCriterionModal(choir, criterion)"
            :choir="choir"
            :criterion="criterion"
            v-bind:key="choir.id"
            v-bind:class="{editing: isEditing(choir, criterion), saving: getSavingStatus(choir.id + '_' + caption.id + '_' + criterion.id), saved: getSavedStatus(choir.id + '_' + caption.id + '_' + criterion.id), errored: getErroredStatus(choir.id + '_' + caption.id + '_' + criterion.id)}"
            >{{ score(choir, criterion) }}</td>
        </tr>
        <!-- Caption Criteria End -->

        <!-- Caption footer start -->
        <tr class="caption-row caption-footer" v-bind:key="caption.id">
          <th class="caption-subtotal caption-subtotal-label" :class="['lighter-background-color-' + caption.color_id]">
            {{ caption.name }} <span v-if="caption.id === 1 && captionWeightingId === 1">Raw</span> Subtotal
            <div v-if="caption.id === 1 && captionWeightingId === 1">{{ caption.name }} Weighted Subtotal</div>
          </th>
          <td
            v-for="choir in choirsList"
            :choir="choir"
            v-bind:key="choir.id"
            class="caption-subtotal caption-subtotal-value"
            :class="['lighter-background-color-' + caption.color_id]"
            >
              {{ getChoirCaptionSubtotalScore(choir, caption) }}
              <div v-if="caption.id === 1 && captionWeightingId === 1">{{ getChoirCaptionSubtotalScore(choir, caption) * 1.5 }}</div>
            </td>
        </tr>
        <tr class="caption-row caption-footer" v-bind:key="caption.id">
          <th class="caption-rank caption-rank-label" :class="['lighter-background-color-' + caption.color_id]">
            {{ caption.name }} Rank
          </th>
          <td
            v-for="choir in choirsList"
            :choir="choir"
            v-bind:key="choir.id"
            class="caption-rank caption-rank-value"
            :class="['lighter-background-color-' + caption.color_id]"
            >
              {{ choirCaptionRank(choir, caption, 'Place') }} <span class="tied-badge" v-if="choirCaptionRankTied(choir, caption)">Tied</span>
            </td>
        </tr>
        <!-- Caption footer end -->

        </template>
        <!-- Caption container end -->

        <!-- Total score start -->
        <tr class="score-row">
          <th class="score-total-label">
            <span v-if="captionWeightingId === 1">Raw</span> Total
            <div v-if="captionWeightingId === 1">Weighted Total</div>
          </th>
          <td
            v-for="choir in choirsList"
            :choir="choir"
            v-bind:key="choir.id"
            class="score-total-value"
            >
            {{ choirTotalScore(choir) }}
            <div v-if="captionWeightingId === 1">{{ choirTotalWeightedScore(choir) }}</div>
          </td>
        </tr>
        <!-- Total score end -->

        <!-- Total score start -->
        <tr class="rank-rating-row">
          <th class="rank-rating-label">
            Rank <span v-if="hasRatings">&amp; Rating</span>
          </th>
          <td
            v-for="choir in choirsList"
            :choir="choir"
            v-bind:key="choir.id"
            class="rank-rating-value"
            >
            {{ choirRank(choir, 'Place') }} <span class="tied-badge" v-if="choirRankTied(choir)">Tied</span><br>
            <span v-if="hasRatings">{{ scoreToRating(choir.total_score) }}</span>
          </td>
        </tr>
        <!-- Rank / Rating end -->

        <!-- Comments -->
        <tr class="comment-row">
          <th class="criterion-name">Comments</th>

          <td
            class="comment-text"
            v-for="choir in choirsList"
            @click="activateChoirCommentModal(choir)"
            :choir="choir"
            v-bind:key="choir.id"
            >
            {{ comment(choir) }}
          </td>
        </tr>
         <!-- Record -->
        <tr class="comment-row" v-if="hasPremium">
          <th class="criterion-name">Record Comments</th>
          <td  v-for="choir in choirsList" :key="choir.id">
            <Record
              :recordsList="recordsList"
              :choir="choir"
              :recordings ="recordings"
              @start-recording="onRecordingStart(choir.id)"
              @stop-recording="currentRecordingId = null"
              @upload-complete="changeInProgressRecValue(-1)"
              @upload-error="warnRecordingSaveError"
            />
          </td>
        </tr>
        <!-- DropZone -->
        <tr class="comment-row" v-if="hasPremium">
          <th class="criterion-name">Upload Recorded File</th>
          <td v-for="choir in choirsList" :key="choir.id">
            <DropZone :choir="choir"
            @upload-start="changeInProgressRecValue(1)"
            @upload-complete="changeInProgressRecValue(-1)"
            @upload-error="warnUploadRecordingError"
            />
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import Record from './Record'
import DropZone from './DropZone'

export default {
  name: 'Spreadsheet',
  components: { Record, DropZone },
  data: function () {
    return {
      activeChoir: null,
      activeCriterion: null,
      audioRecorder: null,
      recordingData: [],
      currentRecordingId: null
    }
  },
  computed: {
    captionWeightingId () {
      return this.$store.getters.captionWeightingId
    },
    choirsList () {
      return this.$store.getters.getChoirsList.map(choir => ({
        ...choir,
        isRecording: choir.id === this.currentRecordingId,
        isDisabled: this.currentRecordingId && this.currentRecordingId !== choir.id
      }))
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
    ratings () {
      return this.$store.state.ratings
    },
    hasRatings () {
      return this.$store.state.ratings.length !== 0
    },
    hasPremium () {
      return this.$store.state.competition.is_premium
    },
    activeModal () {
      return this.$store.state.activeModal
    },
    isSpreadsheetScoringActive () {
      return this.$store.state.isSpreadsheetScoringActive
    },
    division () {
      return this.$store.state.divisions
    },
    recordings () {
      return this.$store.state.recordings
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
    isEditing: function (choir, criterion) {
      var activeChoir = this.$store.getters.activeChoir
      var activeCriterion = this.$store.getters.activeCriterion
      var activeChoirId = (activeChoir) ? activeChoir.id : null
      var activeCriterionId = (activeCriterion) ? activeCriterion.id : null
      return (choir.id === activeChoirId && criterion.id === activeCriterionId && this.activeModal)
    },
    displayScoringInactiveMessage: function () {
      alert('Scoring is currently inactive.')
    },
    activateModal: function (data) {
      this.$store.commit('startModalProtection')
      this.$store.commit('activateModal', data)
    },
    activateChoirModal: function (choir) {
      this.$store.commit('startModalProtection')
      if (this.isSpreadsheetScoringActive) {
        this.$store.commit('activateChoirModal', choir)
      } else {
        this.displayScoringInactiveMessage()
      }
    },
    activateChoirCommentModal: function (choir) {
      this.$store.commit('startModalProtection')
      if (this.isSpreadsheetScoringActive) {
        this.$store.commit('activateChoirCommentModal', choir)
      } else {
        this.displayScoringInactiveMessage()
      }
    },
    activateCriterionModal: function (criterion) {
      this.$store.commit('startModalProtection')
      if (this.isSpreadsheetScoringActive) {
        this.$store.commit('activateCriterionModal', criterion)
      } else {
        this.displayScoringInactiveMessage()
      }
    },
    activateChoirCriterionModal: function (choir, criterion) {
      this.$store.commit('startModalProtection')
      if (this.isSpreadsheetScoringActive) {
        if (!this.activeModal) {
          this.$store.commit('activateChoirCriterionModal', {choir, criterion})
        } else {
          this.deactivateModal()
        }
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
    deactivateModal: function () {
      this.$store.commit('deactivateModal')
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
    choirRank: function (choir, rankNoun = '') {
      var rank = choir.total_score ? this.toOrdinal(choir.rank) : '--'
      return rankNoun ? rank + ' ' + rankNoun : rank
    },
    choirRankTied: function (choir) {
      if (choir.total_score && choir.rank_tied) {
        return true
      }
    },
    updateChoirsRanks () {
      return this.$store.getters.updateChoirsRanks
    },
    choirTotalScore: function (choir) {
      return this.$store.getters.getChoirTotalScore(choir.id)
    },
    choirTotalWeightedScore: function (choir) {
      return this.$store.getters.getChoirTotalWeightedScore(choir.id)
    },
    scoreToRating: function (score) {
      return this.$store.getters.getChoirRating(score)
    },
    getChoirCaptionSubtotalScore: function (choir, caption) {
      return this.$store.getters.getChoirCaptionSubtotalScore(choir.id, caption.id)
    },
    getChoirCaptionRank: function (choir, caption, rankNoun = '') {
      return this.$store.getters.getChoirCaptionRank(choir.id, caption.id)
    },
    choirCaptionRank: function (choir, caption, rankNoun = '') {
      var captionRank = this.getChoirCaptionRank(choir, caption)
      var rank = captionRank.caption_score ? this.toOrdinal(captionRank.rank) : '--'
      return rankNoun ? rank + ' ' + rankNoun : rank
    },
    choirCaptionRankTied: function (choir, caption) {
      var captionRank = this.getChoirCaptionRank(choir, caption)
      if (captionRank.caption_score && captionRank.rank_tied) {
        return true
      }
    },
    comment: function (choir) {
      return this.$store.getters.getChoirComment(choir.id)
    },
    onRecordingStart: function (choirId) {
      this.currentRecordingId = choirId
      this.changeInProgressRecValue(1)
    },
    changeInProgressRecValue: function (value) {
      const input = document.getElementById('recordingsInProgress')
      input.value = parseInt(input.value) + value
    },
    getSavingStatus: function (property) {
      return this.$store.getters.getSavingStatus(property)
    },
    getSavedStatus: function (property) {
      return this.$store.getters.getSavedStatus(property)
    },
    getErroredStatus: function (property) {
      return this.$store.getters.getErroredStatus(property)
    },
    warnRecordingSaveError: function () {
      alert('There was an error saving your recording to the server.  You can save your recording to your device using the link in the recording name.  Then refresh this page and try uploading the file.')
    },
    warnUploadRecordingError: function () {
      alert('There was an error uploading your file to the server.  Please refresh this page and try uploading the file again.')
    }
  },
  mounted () {
    this.updateChoirsRanks()
  },
  beforeUpdate () {
    this.updateChoirsRanks()
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>

/* @import url('https://showchoir.carmenscoring.com/css/dynamic-colors.css'); */

::-webkit-scrollbar {
  height: 10px;
  width: 10px;
}

::-webkit-scrollbar-track {
  background: #eee;
}

::-webkit-scrollbar-thumb {
  background: #666;
}

#spreadsheet {
  margin: 5px;
  margin-top:5px;
  position: relative;
  width: calc(100% - 10px);
  height: 100%;
  z-index: 1;
  overflow: scroll;

  &.fixed {
    //position: fixed;
  }

  &.spaceBelow {
    padding-bottom: 280px;
    height: calc(100% + 280px);
  }

  th, td {
    padding: 5px 10px;
    border: 1px solid #ddd;
    background: #fff;
    vertical-align: top;
    font-weight: normal;
  }

  td.editing {
    outline: 4px #7F4091 solid;
    border: 1px #7F4091 solid;
    background-color: #7F4091;
    color: #ffffff;
    font-weight: 700;
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
    max-width: 200px;
    min-width: 150px;
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
  width: auto;
  min-width: 100%;
  margin: auto;
  table-layout: fixed;
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

  .tied-badge {
    font-size: 11px;
    padding: 3px 7px 3px 6px;
    border-radius: 12px;
  }

  .choir-header,
  .caption-value {
    min-width: 150px;
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
      td.caption-subtotal, th.caption-subtotal, td.caption-rank, th.caption-rank {
        color: white;
        padding: 5px;

        &.caption-subtotal-label, &.caption-rank-label {
          text-align: right;
          padding-right: 10px;
        }

        &.caption-subtotal-value, &.caption-rank-value {
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

  tr.caption-footer {
    .tied-badge {
      padding: 2px 6px 2px 5px;
      background: transparent;
      color: #ffffff;
      font-weight: 900 !important;
      border: 2px solid #ffffff;
    }
  }

  tr.rank-rating-row {
    .tied-badge {
      background: #b84660;
      color: #ffffff;
    }
  }

   tr.comment-row {
    font-size: 13px;

    .comment-text {
      font-size: 15px;
    }
  }

  tr.record-row {
    font-size: 13px;
  }

  tr.criteria-row.active {
    height: 100px;
  }
}
</style>

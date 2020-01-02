<template>
  <Modal>
    <ModalHeader>
      <h1>{{ choir.name }}</h1>
    </ModalHeader>
    <ModalSubheader>
      <div class="tab-buttons-container">

        Toggle Caption to Score:
        <button
          type="button"
          class="caption-tab-button"
          v-for="caption in captionsList"
          :key="caption.id"
          @click="activateCaption(caption)"
          :class="['background-color-' + caption.color_id]"
        >
          {{ caption.name }}
        </button>
      </div>
    </ModalSubheader>
    <ModalBody>

      <!-- Caption container start -->
      <template v-for="caption in captionsList">

        <CriteriaScores :criteriaList="criteriaList.filter(cr => cr.caption_id === caption.id)" :choir="choir" :scores="scores" :caption="caption" v-if="activeCaption === caption" v-bind:key="caption.id"></CriteriaScores>

      </template>
      <!-- Caption container end -->

      <table>
        <!-- Total score start -->
        <tr class="score-row">
          <td class="score-total-label">
            Total
          </td>
          <td class="score-total-value score-column">
            {{ choirTotalScore }}
          </td>
        </tr>
        <!-- Total score end -->
      </table>

    </ModalBody>
  </Modal>
</template>

<script>
import Modal from './Modal'
import ModalHeader from './ModalHeader'
import ModalSubheader from './ModalSubheader'
import ModalBody from './ModalBody'
import ModalFooter from './ModalFooter'
import CriteriaScores from './CriteriaScores'

export default {
  name: 'ChoirModal',
  components: {
    Modal,
    ModalHeader,
    ModalSubheader,
    ModalBody,
    ModalFooter,
    CriteriaScores
  },
  data: function () {
    return {
      criteriaList: this.$store.state.criteriaList,
      captionsList: this.$store.state.captionsList,
      activeCaption: this.$store.state.captionsList[0]
    }
  },
  computed: {
    choir () {
      return this.$store.getters.activeChoir
    },
    scores () {
      return this.$store.getters.activeChoirScores
    },
    choirTotalScore () {
      return this.$store.getters.getChoirTotalScore(this.$store.getters.activeChoir.id)
    }
  },
  methods: {
    activateCaption: function (caption) {
      this.activeCaption = caption
    }
  }
}

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
.tab-buttons-container {
}

.caption-tab-button {
    background: #ccc;
    border: none;
    color: #fff;
    padding: 5px 10px;
    border-radius: 5px;
    margin: 0 5px;
    cursor: pointer;
}

table {
  width: 100%;
  max-width: 700px;
  position: relative;
  margin: auto;

  td.criterion-column {
    padding-right: 20px;
    text-align: right;

  }

  td.score-column {
    width: 200px;
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
        width: 110px;
      }
      &.score-total-value {
        padding: 8px 0
      }
    }
  }
}

</style>

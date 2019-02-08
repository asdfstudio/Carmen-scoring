<template>
  <Modal>
    <ModalHeader>
      <h1>{{ criterion.name }}</h1>

      <CriterionScoringRange :min="criterion.minScore" :max="criterion.maxScore" :increment="criterion.increment" :displayType="criterionScoringRangeDisplayType" />
    </ModalHeader>
    <ModalSubheader>
      <p>
        {{ criterion.description }}
      </p>
    </ModalSubheader>
    <ModalBody>

      <ChoirScores :criterion="criterion" :choirsList="choirsList" :scores="scores"></ChoirScores>

    </ModalBody>
  </Modal>
</template>

<script>
import Modal from './Modal'
import ModalHeader from './ModalHeader'
import ModalSubheader from './ModalSubheader'
import ModalBody from './ModalBody'
import ModalFooter from './ModalFooter'
import Score from './Score'
import ChoirScores from './ChoirScores'
import CriterionScoringRange from './CriterionScoringRange'

export default {
  name: 'CriterionModal',
  components: {
    Modal,
    ModalHeader,
    ModalSubheader,
    ModalBody,
    ModalFooter,
    Score,
    ChoirScores,
    CriterionScoringRange
  },
  data: function () {
    return {
      criterionScoringRangeDisplayType: 'expanded'
    }
  },
  computed: {
    criterion () {
      return this.$store.getters.activeCriterion
    },
    choirsList () {
      return this.$store.getters.getChoirsList
    },
    scores () {
      return this.$store.getters.getCriterionScores(this.criterion.id)
    }
  },
  watch: {
  },
  methods: {
  }
}

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>

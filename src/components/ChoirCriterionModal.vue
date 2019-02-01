<template>
  <Modal>
    <ModalHeader>
      <h2>{{ choir.name }}</h2>
      <h1>{{ criterion.name }}</h1>
    </ModalHeader>
    <ModalBody>

      <Score
        :min="criterion.minScore"
        :max="criterion.maxScore"
        :initialScore="currentScore"
        :increment="criterion.increment"
        :showIncrements="true"
        :showScoreChoices="true"
        :showScoringRange="true"
        :incrementOutlined="true"
        :isScoringActive="true"
        :displayType="displayType"
        :choirId="choir.id"
        :criterionId="criterion.id"
        :captionId="criterion.caption_id"
      ></Score>
    </ModalBody>
    <ModalFooter>
      <p>
        {{ criterion.description }}
      </p>
    </ModalFooter>
  </Modal>
</template>

<script>
import Modal from './Modal'
import ModalHeader from './ModalHeader'
import ModalBody from './ModalBody'
import ModalFooter from './ModalFooter'
import Score from './Score'

export default {
  name: 'ChoirCriterionModal',
  components: {
    Modal,
    ModalHeader,
    ModalBody,
    ModalFooter,
    Score
  },
  data: function () {
    return {
      displayType: 'expanded'
    }
  },
  computed: {
    criterion () {
      return this.$store.getters.activeCriterion
    },
    choir () {
      return this.$store.getters.activeChoir
    },
    currentScore () {
      return this.$store.getters.getChoirCriterionScore(this.choir.id, this.criterion.id)
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

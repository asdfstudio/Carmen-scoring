<template>
  <Modal v-hotkey="keymap">
    <ModalBody>
      <Score
        ref="score"
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
    },
    keymap () {
      return {
        '1': this.writeScore.bind(this, 1),
        '2': this.writeScore.bind(this, 2),
        '3': this.writeScore.bind(this, 3),
        '4': this.writeScore.bind(this, 4),
        '5': this.writeScore.bind(this, 5),
        '6': this.writeScore.bind(this, 6),
        '7': this.writeScore.bind(this, 7),
        '8': this.writeScore.bind(this, 8),
        '9': this.writeScore.bind(this, 9),
        'u': this.incrementScore,
        'd': this.decrementScore,
      }
    }
  },
  watch: {
  },
  methods: {
    incrementScore () {
      this.$refs.score.up()
    },
    decrementScore () {
      this.$refs.score.down()
    },
    writeScore (newScore) {
      this.$refs.score.change(newScore)
    }
  }
}

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
#modal.choirCriterion {
  width: auto;
  max-width: 100%;
  background: #ffffff;
  padding: 0;
  margin: 0;
  overflow: hidden;
  border-radius: 0;
  z-index: 100;
  position: fixed;
  top: auto;
  bottom: 0;
  left: 0;
  right: 0;
  box-shadow: 0 0 50px rgba(0,0,0,.5);
}
</style>

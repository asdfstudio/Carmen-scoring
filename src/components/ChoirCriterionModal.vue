<template>
  <Modal>
    <GlobalEvents
      @keyup.digit1="writeScore(1)"
      @keyup.digit2="writeScore(2)"
      @keyup.digit3="writeScore(3)"
      @keyup.digit4="writeScore(4)"
      @keyup.digit5="writeScore(5)"
      @keyup.digit6="writeScore(6)"
      @keyup.digit7="writeScore(7)"
      @keyup.digit8="writeScore(8)"
      @keyup.digit9="writeScore(9)"
      @keyup.digit0="writeScore(10)"
      @keyup.+="incrementScore"
      @keyup.period="incrementScore"
      @keyup.-="decrementScore"
    />
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
import GlobalEvents from 'vue-global-events'
import Swal from 'sweetalert2'

export default {
  name: 'ChoirCriterionModal',
  components: {
    Modal,
    ModalHeader,
    ModalBody,
    ModalFooter,
    Score,
    GlobalEvents
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
    },
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

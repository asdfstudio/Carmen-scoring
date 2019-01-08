<template>
  <div id="app">

    <SpreadsheetHeader></SpreadsheetHeader>

    <Spreadsheet v-bind:class="{fixed: activeModal}"/>

    <div @click="deactivateModal" v-if="activeModal" id="modal-cover"></div>

    <CriterionModal v-if="activeModalType === 'criterion'"/>

    <ChoirModal v-if="activeModalType === 'choir'"/>

    <ChoirCommentModal v-if="activeModalType === 'choirComment'"/>

    <ChoirCriterionModal v-if="activeModalType === 'choirCriterion'"/>

  </div>
</template>

<script>
import Spreadsheet from './components/Spreadsheet'
import CriterionModal from './components/CriterionModal'
import ChoirCriterionModal from './components/ChoirCriterionModal'
import ChoirModal from './components/ChoirModal'
import ChoirCommentModal from './components/ChoirCommentModal'
import SpreadsheetHeader from './components/SpreadsheetHeader'

export default {
  name: 'App',
  components: {
    SpreadsheetHeader,
    Spreadsheet,
    CriterionModal,
    ChoirCriterionModal,
    ChoirCommentModal,
    ChoirModal
  },
  computed: {
    scores () {
      return this.$store.state.scores
    },
    activeChoir () {
      return this.$store.getters.activeChoir
    },
    activeCriterion () {
      return this.$store.getters.activeCriterion
    },
    activeModal () {
      return this.$store.state.activeModal
    },
    activeComment () {
      return this.$store.state.activeComment
    },
    activeModalType () {
      if (!this.activeModal) {
        return null
      }

      if (this.activeChoir && this.activeCriterion) {
        return 'choirCriterion'
      } else if (this.activeChoir && this.activeComment) {
        return 'choirComment'
      } else if (this.activeChoir) {
        return 'choir'
      } else if (this.activeCriterion) {
        return 'criterion'
      }
    }
  },
  methods: {
    deactivateModal: function () {
      this.$store.commit('deactivateModal')
    }
  }
}
</script>

<style>
body {
  background: #ddd;
  margin: 0;
}
#app {
  font-family: 'Segoe UI', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  padding: 0;
  background: #ddd;

}

#modal-cover {
  background: #333;
  opacity: 0.8;
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  z-index: 10;
}

.clickable {
  /*border-bottom: 1px dotted;*/
  cursor: pointer;
}
</style>

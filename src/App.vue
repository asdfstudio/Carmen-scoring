<template>
  <div id="app">

    <SpreadsheetHeader></SpreadsheetHeader>

    <Spreadsheet v-bind:class="[{fixed: activeModal && activeModalType !== 'choirCriterion'}, {spaceBelow: activeModal && activeModalType === 'choirCriterion'}]"/>

    <div v-if="activeModal && activeModalType !== 'choirCriterion'" id="modal-cover" v-bind:class="activeModalType"></div>

    <CriterionModal v-if="activeModalType === 'criterion'" v-bind:class="activeModalType"/>

    <ChoirModal v-if="activeModalType === 'choir'" v-bind:class="activeModalType"/>

    <ChoirCommentModal v-if="activeModalType === 'choirComment'" v-bind:class="activeModalType"/>

    <ChoirCriterionModal v-if="activeModalType === 'choirCriterion'" v-bind:class="activeModalType"/>

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
    protectModal () {
      return this.$store.state.protectModal
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
    deactivateModal: function (e) {
      if (this.activeModal && !this.protectModal) {
        this.$store.commit('deactivateModal')
      }
      this.$store.commit('endModalProtection')
    }
  },
  mounted: function () {
    document.addEventListener('click', this.deactivateModal)
  }
}
</script>

<style>
body {
  background: #eee;
  margin: 0;
}
#app {
  font-family: "Lato", "Helvetica Neue", Helvetica, Arial, sans-serif;
  font-size: 14px;
  line-height: 1.42;
  text-align: center;
  color: #333333;
  padding: 0;

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

#modal-cover.choirCriterion {
  opacity: .25;
}

.clickable {
  /*border-bottom: 1px dotted;*/
  cursor: pointer;
}
</style>

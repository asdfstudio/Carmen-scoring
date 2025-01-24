<template>
  <Modal>
    <ModalHeader>
      <h1>{{ choir.name }}</h1>
      <h3>Feedback</h3>
    </ModalHeader>
    <ModalSubheader>
      <p>Please keep your feedback positive and constructive.</p>
    </ModalSubheader>
    <ModalBody>
      <textarea v-model.lazy="aiCommentView"></textarea>
      <!-- <button class="button" type="submit" @click="saveAIComment()">Save Comment</button> -->
      <button class="button cancel" type="submit" @click="cancelComment()">Cancel</button>
    </ModalBody>
  </Modal>
</template>

<script>
import Modal from './Modal'
import ModalHeader from './ModalHeader'
import ModalSubheader from './ModalSubheader'
import ModalBody from './ModalBody'
import ModalFooter from './ModalFooter'

export default {
  name: 'ChoirAICommentViewModal',
  components: {
    Modal,
    ModalHeader,
    ModalBody,
    ModalFooter,
    ModalSubheader
  },
  data: function () {
    return {
      initialAICommentView: this.$store.getters.getChoirAICommentView(this.$store.getters.activeChoir.id),
      aiCommentView: this.$store.getters.getChoirAICommentView(this.$store.getters.activeChoir.id)
    };
  },
  methods: {
    cancelComment: function () {
      const payload = {
        choir_id: this.choir.id,
        round_id: this.choir.round_id,
        ai_comment_view: this.initialAICommentView,
      };
      this.$store.dispatch('setAICommentView', payload);
      this.$store.commit('deactivateModal');
    },
  },
  computed: {
    choir () {
      return this.$store.getters.activeChoir
    },
    comment: {
      get: function () {
        return this.$store.getters.getChoirAICommentView(this.choir.id)
      },
      set: function (newValue) {
        this.aiComment = newValue
        /* const payload = {
          choir_id: this.choir.id,
          round_id: this.choir.round_id,
          comment: newValue
        }
        this.$store.dispatch('setComment', payload) */
      }
    }
  },
  mounted: function () {
    const payload = {
        choir_id: this.choir.id,
        round_id: this.choir.round_id,
        ai_comment_view: this.initialAICommentView,
      };
    this.$store.dispatch('setAICommentView', payload)
  }
}

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
textarea {
  width: 100%;
  height: 200px;
  max-height: 100%;
  max-width: 600px;
  margin: 15px 0;
  padding: 10px;
  border: 1px solid #F0F0F0;
  font-size: 15px;
  box-sizing: border-box;

  &:focus {
    border: 1px solid #7F4091;
  }
}

button, .button {
  background: #7F4091;
  color: #fff;
  padding: 10px 15px;
  margin: 0 5px;
  text-align: center;
  border: none;
  border-radius: 5px;

  &.cancel {
    background: #ffffff;
    padding: 9px 14px;
    border: 1px solid #CA2128;
    color: #CA2128;
  }
}
</style>

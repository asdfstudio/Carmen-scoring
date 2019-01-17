<template>
  <Modal>
    <ModalHeader>
      <h1>{{ choir.name }}</h1>
      <h3>Feeback</h3>
    </ModalHeader>
    <ModalSubheader>
      <p>Please keep your feedback positive and constructive.</p>
    </ModalSubheader>
    <ModalBody>
      <textarea v-model.lazy="comment"></textarea>
      <button class="button" type="submit" @click="saveComment()">Save Comment</button>
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
  name: 'ChoirCommentModal',
  components: {
    Modal,
    ModalHeader,
    ModalBody,
    ModalFooter,
    ModalSubheader
  },
  data: function () {
    return {
      initialComment: this.$store.getters.getChoirComment(this.choir.id),
      currentComment: this.$store.getters.getChoirComment(this.choir.id)
    }
  },
  methods: {
    saveComment: function (event) {
      const payload = {
        choir_id: this.choir.id,
        round_id: this.choir.round_id,
        comment: this.currentComment
      }
      this.$store.dispatch('setComment', payload)
      this.$store.commit('deactivateModal')
    }
  },
  computed: {
    choir () {
      return this.$store.getters.activeChoir
    },
    comment: {
      get: function () {
        return this.$store.getters.getChoirComment(this.choir.id)
      },
      set: function (newValue) {
        this.currentComment = newValue
        /* const payload = {
          choir_id: this.choir.id,
          round_id: this.choir.round_id,
          comment: newValue
        }
        this.$store.dispatch('setComment', payload) */
      }
    }
  }
}

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
textarea {
  width: 600px;
  height: 200px;
  max-height: 100%;
  max-width: 100%;
  margin: 15px;
  padding: 10px;
  border: 1px solid #F0F0F0;

  &:focus {
    border: 1px solid #7F4091;
  }
}

button, .button {
  background: #7F4091;
  color: #fff;
  padding: 10px 15px;
  text-align: center;
  border: none;
  border-radius: 5px;
}
</style>

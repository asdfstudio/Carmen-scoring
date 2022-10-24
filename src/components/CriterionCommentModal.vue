<template>
  <Modal>
    <ModalHeader>
      <h1>{{ criterion.name }}</h1>
      <h3>Feedback</h3>
    </ModalHeader>
    <ModalSubheader>
      <p>Please keep your feedback positive and constructive.</p>
    </ModalSubheader>
    <ModalBody>
      <textarea v-model="comment"></textarea>
      <button class="button" type="submit" @click="saveComment()">
        Save Comment
      </button>
      <button class="button cancel" type="submit" @click="cancelComment()">
        Cancel
      </button>
    </ModalBody>
  </Modal>
</template>

<script>
import Modal from "./Modal";
import ModalHeader from "./ModalHeader";
import ModalSubheader from "./ModalSubheader";
import ModalBody from "./ModalBody";
import ModalFooter from "./ModalFooter";

export default {
  name: "CriterionCommentModal",
  components: {
    Modal,
    ModalHeader,
    ModalBody,
    ModalFooter,
    ModalSubheader,
  },
  data: function () {
    return {
      currentComment: this.$store.getters.getChoirCriterionComment(
        this.$store.state.activeChoirCriterionComment.choirId,
        this.$store.state.activeChoirCriterionComment.criterionId
      ),
    };
  },
  methods: {
    autosaveComment: function (event) {
      const payload = {
        choir_id: this.choir.id,
        round_id: this.choir.round_id,
        criteria_id: this.criterion.id,
        comment: event.target.value,
      };
      this.$store.dispatch("setComment", payload);
    },
    saveComment: function (event) {
      const payload = {
        choir_id: this.choir.id,
        round_id: this.choir.round_id,
        criteria_id: this.criterion.id,
        comment: this.currentComment,
      };
      this.$store.dispatch("setComment", payload);
      this.$store.commit("deactivateModal");
    },
    cancelComment: function (event) {
      this.$store.commit("deactivateModal");
    },
  },
  computed: {
    choir() {
      return this.$store.state.choirsList.find(
        (choir) =>
          choir.id === this.$store.state.activeChoirCriterionComment.choirId
      );
    },
    criterion() {
      return this.$store.state.criteriaList.find(
        (criterion) =>
          criterion.id ===
          this.$store.state.activeChoirCriterionComment.criterionId
      );
    },
    comment: {
      get: function () {
        return this.currentComment;
      },
      set: function (newValue) {
        this.currentComment = newValue;
      },
    },
  },
  mounted: function () {
    var self = this;
    document
      .getElementsByTagName("textarea")[0]
      .addEventListener("input", function (event) {
        self.autosaveComment(event);
      });
  },
};
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
  border: 1px solid #f0f0f0;
  font-size: 15px;
  box-sizing: border-box;

  &:focus {
    border: 1px solid #7f4091;
  }
}

button,
.button {
  background: #7f4091;
  color: #fff;
  padding: 10px 15px;
  margin: 0 5px;
  text-align: center;
  border: none;
  border-radius: 5px;

  &.cancel {
    background: #ffffff;
    padding: 9px 14px;
    border: 1px solid #ca2128;
    color: #ca2128;
  }
}
</style>

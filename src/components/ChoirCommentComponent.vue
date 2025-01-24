<template>
    <div>
      <div>
        {{ currentChoir }}
      </div>
      <textarea v-model="currentComment"></textarea>

      <button @click="save()">Save Comment</button>
    </div>
</template>

<script>
    //import Vue from 'vue';

    export default Vue.component('choir-comment', {
        props: ['comment', 'ai_comment', 'choir'],
        data: function() {
            return {
                currentChoir: this.choir,
                currentComment: this.comment,
                aiComment: this.ai_comment
            }
        },
        methods: {
          save: function() {
            console.log(this.currentComment);
          }
        }
    });
</script>

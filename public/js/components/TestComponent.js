
// import Vue from 'vue';

export default Vue.component('hello', {
  template: '<div class="hello">{{ greeting }} <span><slot></slot></span>!</div>',
  data () {
    return {
      greeting: 'Hello'
    }
  }
})

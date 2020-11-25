// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'

import { store } from './store/index'
import App from './App'

Vue.config.productionTip = false

/* Handy for global events and scoring */
Vue.config.keyCodes.digit0 = 48
Vue.config.keyCodes.digit1 = 49
Vue.config.keyCodes.digit2 = 50
Vue.config.keyCodes.digit3 = 51
Vue.config.keyCodes.digit4 = 52
Vue.config.keyCodes.digit5 = 53
Vue.config.keyCodes.digit6 = 54
Vue.config.keyCodes.digit7 = 55
Vue.config.keyCodes.digit8 = 56
Vue.config.keyCodes.digit9 = 57
Vue.config.keyCodes.period = 190

/* eslint-disable no-new */
new Vue({
  el: '#app',
  store,
  components: { App },
  template: '<App/>'
})

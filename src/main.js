// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'

import { store } from './store/index'
import App from './App'
import VueHotKey from 'v-hotkey'

Vue.config.productionTip = false
Vue.use(VueHotKey)

/* eslint-disable no-new */
new Vue({
  el: '#app',
  store,
  components: { App },
  template: '<App/>'
})

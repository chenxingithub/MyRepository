import Vue from 'vue'
import App from './App'
import router from './router'
import store from './store/index'

import 'normalize.css'
import './plugins/flexible'

// import Vconsole from 'vconsole'
// const vConsole = new Vconsole();

Vue.config.productionTip = false

window.lib.flexible.refreshRem();

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  render: h => h(App)
})

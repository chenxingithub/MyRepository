// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import store from './store/index'

import  VueQuillEditor from 'vue-quill-editor';//用于渲染后台编辑器的样式
import 'quill/dist/quill.core.css';
import './utils/rem'
import 'babel-polyfill'

Vue.config.productionTip = false

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  components: { App },
  template: '<App/>'
})

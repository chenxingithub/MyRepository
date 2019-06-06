// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import App from './App'
/*import VueResource from 'vue-resource';*/
import axios from 'axios';
import store from './store/'; // vuex 数据存储所需对象
import router from './router'
import '../static/UE/ueditor.config.js';
import '../static/UE/ueditor.all.min.js';
import '../static/UE/lang/zh-cn/zh-cn.js';
import '../static/UE/ueditor.parse.min.js';
import  VueQuillEditor from 'vue-quill-editor';
import 'quill/dist/quill.core.css';
import 'quill/dist/quill.snow.css';
import 'quill/dist/quill.bubble.css';

/*Vue.config.productionTip = false*/
Vue.prototype.$axios=axios;
/*Vue.use(VueResource);*/
Vue.use(ElementUI)
Vue.use(VueQuillEditor, /* { default global options } */)

/* eslint-disable no-new */
new Vue({
  el:'#app',
  store,
  router,
  render: h => h(App)
})

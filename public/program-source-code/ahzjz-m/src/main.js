// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
// import axios from 'axios'
import store from './store/index'
import './config/rem'
// import VueAwesomeSwiper from 'vue-awesome-swiper'

Vue.config.productionTip = false

router.afterEach((to,from,next) => {//路由跳转后页面滚动到顶部
  window.scrollTo(0,0);
});
// Vue.prototype.$ajax = axios;

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  components: { App },
  template: '<App/>'
});

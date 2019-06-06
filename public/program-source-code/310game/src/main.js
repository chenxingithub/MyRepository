// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import store from './store/index'
import storejs from 'store'

import { Pagination, Select } from 'element-ui';
Vue.use(Pagination);

Vue.prototype.storejs = storejs;

import 'normalize.css'
import 'babel-polyfill'

//ie9 兼容
if (Number.parseInt === undefined) Number.parseInt = window.parseInt;
if (Number.parseFloat === undefined) Number.parseFloat = window.parseFloat;

router.afterEach((to, from, next) => {

  if(to.name == 'servicer') {//跳客服中心页面回到顶部
    window.scrollTo(0,0);
  }
})

Vue.config.productionTip = false

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  components: { App },
  template: '<App/>'
})

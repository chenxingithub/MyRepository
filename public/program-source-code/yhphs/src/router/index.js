import Vue from 'vue'
import Router from 'vue-router'
import Home from '../views/home/index.vue'
import Article from '../views/article/index.vue'
import articleList from '../views/articleList/index.vue'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'Home',
      component: Home,
      meta: {
        keepAlive: true
      }
    },
    {
      path: '/Article',
      name: 'Article',
      component: Article,
      meta: {
        keepAlive: true
      }
    },
    {
      path: '/articleList',
      name: 'articleList',
      component: articleList,
      meta: {
        keepAlive: true
      }
    }
  ]
})

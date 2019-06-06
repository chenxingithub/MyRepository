import Vue from 'vue'
import Router from 'vue-router'
import HomePage from '../view/homePage'
import article from '../view/article/index.vue'
import articleList from '../view/articleList/index.vue'
import giftBag from '../view/giftBag/index.vue'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'HomePage',
      component: HomePage
    },
    {
      path: '/article',
      name: 'article',
      component: article
    },
    {
      path: '/articleList',
      name: 'articleList',
      component: articleList
    },
    {
      path: '/giftBag',
      name: 'giftBag',
      component: giftBag
    }
  ]
})

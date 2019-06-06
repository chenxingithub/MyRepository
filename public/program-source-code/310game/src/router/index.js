import Vue from 'vue'
import Router from 'vue-router'
// import HelloWorld from '@/components/HelloWorld'
import HomePage from '../view/homePage/index.vue'
import MobileGameList from '../view/mobileGameList/index.vue'
import article from '../view/article/index.vue'
import servicer from '../view/servicer/index.vue'
import  userCenter from '../view/userCenter/index.vue'
import userAdmin from '../view/userCenter/user/index.vue'
import bindTel from '../view/userCenter/user/bindTel.vue'
import modifyPassword from '../view/userCenter/user/modifyPassword.vue'
import realNameAuth from '../view/userCenter/user/realNameAuth.vue'
import compyIntrd from '../view/compyIntrd/index.vue'
import newsInfo from '../view/newsInfo/index.vue'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'HomePage',
      component: HomePage
    },
    {
      path: '/MobileGameList',
      name: 'MobileGameList',
      component: MobileGameList
    },
    {
      path: '/article',
      name: 'article',
      component: article
    },
    {
      path: '/servicer',
      name: 'servicer',
      component: servicer
    },
    {
      path: '/compyIntrd',
      name: 'compyIntrd',
      component: compyIntrd
    },
    {
      path: '/userCenter',
      name: 'userCenter',
      component: userCenter,
      children: [
        {
          path: 'uAdmin',
          name: 'uAdmin',
          component: userAdmin
        },
        {
          path: 'bindTel',
          name: 'bindTel',
          component: bindTel
        },
        {
          path: 'modifyPwd',
          name: 'modifyPwd',
          component: modifyPassword
        },
        {
          path: 'nameAuth',
          name: 'nameAuth',
          component: realNameAuth
        }
      ]
    },
    {
      path: '/newsInfo',
      name: 'newsInfo',
      component: newsInfo
    }
  ]
})

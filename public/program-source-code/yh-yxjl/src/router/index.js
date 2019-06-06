import Vue from 'vue'
import Router from 'vue-router'
import home from '@/components/index'
import Hotspot from '@/views/Hotspot/hotspot'
import Dialog from '@/views/Dialog/dialog'
import Feedback from '@/views/Feedback/feedback'
import Recommend from '@/views/Recommend/recommend'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      component: home,
      children: [
        {
          path: 'hotspot',
          name: 'hotspot',
          component: Hotspot
        },
        {
          path: 'dialog',
          name: 'dialog',
          component: Dialog
        },
        {
          path: 'feedback',
          name: 'feedback',
          component: Feedback
        },
        {
          path: 'recommend',
          name: 'recommend',
          component: Recommend
        },
        {
          path: '',
          redirect: 'hotspot'
        },
      ]
    }
  ]
})

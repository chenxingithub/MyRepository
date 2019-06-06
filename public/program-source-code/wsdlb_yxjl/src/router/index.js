import Vue from 'vue'
import Router from 'vue-router'
import index from '@/components/index.vue'
import hotspot from '@/views/Hotspot/Hotspot.vue'
import recommend from '@/views/Recommend/Recommend.vue'
import activity from '@/views/Activity/activity.vue'
import feedback from '@/views/Feedback/Feedback.vue'
import dialog from '@/views/Dialog/dialog.vue'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      component: index,
      children: [
        {
          path: '',
          redirect: 'hotspot'
        },
        {
          path: 'hotspot',
          component: hotspot,
          name: 'hotspot'
        },
        {
          path: 'recommend',
          component: recommend,
          name: 'recommend'
        },
        {
          path: 'activity',
          component: activity,
          name: 'activity'
        },
        {
          path: 'feedback',
          component: feedback,
          name: 'feedback'
        },
        {
          path: 'dialog',
          component: dialog,
          name: 'dialog'
        }
      ]
    }
  ]
})

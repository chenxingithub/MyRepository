import Vue from 'vue'
import Router from 'vue-router'
import index from '../components'
import EntryPage from '@/components/officalEntryPage/index.vue'
import homePage from '@/components/homePage/index.vue'
import listNav from '@/components/list-navigation/index.vue'
import activityPage from '@/components/avtivity-page/index.vue'
import giftBag from '@/components/giftbag/index.vue'
import service from '@/components/service/index.vue'
import openService from '@/components/open-service/index.vue'
import articleDetails from '@/components/article-detail/index.vue'
import download from "../components/downloadMiddlePage/download.vue";
import getRecord from '../components/giftbag/getRecord.vue'
import giftAll from '../components/giftbag/giftAll.vue'
import howExchange from '../components/giftbag/howToExchange.vue'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      component: index,
      children: [
        { path: '',
          redirect: '/entry'
        },
        {
          path: '/entry',
          component: EntryPage
        },
        {
          path: '/index',
          name: 'homePage',
          component: homePage
        },
        {
          path: '/listNav',
          name: 'listNav',
          component: listNav
        },
        {
          path: '/signin',
          name: 'activityPage',
          component: activityPage
        },
        {
          path: '/giftbag',
          component: giftBag,
          children: [
            {path: '', redirect: '/giftbag/giftAll'},
            {
              path: 'giftAll',
              name: 'giftAll',
              component: giftAll
            },
            {
              path: 'getRecord',
              name: 'getRecord',
              component: getRecord
            },
            {
              path: 'howExchange',
              name: 'howExchange',
              component: howExchange
            }
          ]
        },
        {
          path: '/service',
          name: 'service',
          component: service
        },
        {
          path: '/openserivce',
          name: 'openService',
          component: openService
        },
        {
          path: '/articledetails',
          name: 'articleDetails',
          component: articleDetails
        },
        {
          path: '/download',
          component: download
        }
      ]
    }
  ]
})

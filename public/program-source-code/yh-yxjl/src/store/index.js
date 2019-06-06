import Vue from 'vue'
import Vuex from 'vuex'

import {
  GetQueryString
} from '../utils/utils'

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    baseConf: null,
    domain: 'https://www.310game.com',
    imgDomain: 'https://cdn3.ibingniao.com',
    dialogList: [],
    feedBackList: [],
    feedbackTip: true,
    dialogToTop: 0,
    baseInfo: null,
    urlParams: {
      game_id: GetQueryString('game_id') || 18,
      role_id: GetQueryString('role_id') || '33',
      user_id: GetQueryString('user_id') || '33',
      service_id: GetQueryString('service_id') || '',
      role_name: GetQueryString('role_name') || '33',
      vip: GetQueryString('vip') || '33',
      role_rank: GetQueryString('role_rank') || '33',
      channel_id: GetQueryString('channel_id') || '2'
    },
    feedBackMsg: 'hide'//反馈是否有新消息（按钮小红点状态）
  },
  mutations: {
    saveBaseConf(state, payload) {
      state.baseConf = payload;
    },
    pushDialogList(state, payload) {
      state.dialogList.push(payload);
    },
    pushFeedBackList(state, payload) {
      state.feedBackList.push(payload);
    },
    feedBackHistory(state, payload) {
      // state.feedBackList = [...payload, ...state.feedBackList];
      state.feedBackList = payload;
    },
    dialogListToTop(state) {
      state.dialogToTop = state.dialogToTop + 1;
    },
    feedbackTipMut(state) {
      state.feedbackTip = false;
    },
    handleSaveBaseInfo(state, plyload) {
      state.baseInfo = plyload;
    },
    handleAnswerFeedback(state, plyload) {//解决，未解决反馈
      state.dialogList.splice(plyload.index, 1, plyload.text);
    },
    handleFeedBackMsgStatus(state, plyload) {
      // console.log(payload);
      state.feedBackMsg = plyload;
    }
  },
  actions: {
    syncPushDialogList({
      commit,
      state
    }, payload) {

      setTimeout(() => {

        commit('pushDialogList', payload);
      }, 200);
    }
  }
});

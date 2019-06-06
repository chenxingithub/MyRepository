import Vue from 'vue'
import Vuex from 'vuex'

import { GetQueryString } from '../utils/utils.js'

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
        urlParams: {
            game_id: GetQueryString('game_id') || 13,
            role_id: GetQueryString('role_id') || '0',
            user_id: GetQueryString('user_id') || '0',
            service_id: GetQueryString('service_id') || '0',
            role_name: GetQueryString('role_name') || '0',
            vip: GetQueryString('vip') || '0',
            role_rank: GetQueryString('role_rank') || '0',
            channel_id: GetQueryString('channel_id') || '0'
        }
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
        }
    },
    actions: {
        syncPushDialogList({ commit, state }, payload) {

            setTimeout(() => {

                commit('pushDialogList', payload);
            }, 200);
        }
    }
});

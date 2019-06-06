import Vue from 'vue'
import Vuex from 'vuex'

import { GetQueryString } from '../utils/utils.js'

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        baseConf: null,
        domain: 'https://www.310game.com',
        dialogList: [],
        feedBackList: [],
        feedbackTip: true,
        dialogToTop: 0,
        urlParams: {
            game_id: GetQueryString('game_id') || 15,
            role_id: GetQueryString('role_id') || '',
            user_id: GetQueryString('user_id') || '',
            service_id: GetQueryString('service_id') || '',
            role_name: GetQueryString('role_name') || '',
            vip: GetQueryString('vip') || '',
            role_rank: GetQueryString('role_rank') || '',
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
    },
    getters: {

    },

});
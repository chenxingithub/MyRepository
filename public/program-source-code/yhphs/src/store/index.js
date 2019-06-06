import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex);

import { IMG_DOMAIN } from '../globalConf/index'

export default new Vuex.Store({
  state: {
    domain: '',
    imgDomain: IMG_DOMAIN,
    pc_url: 'https://www.310game.com/newcms2/ahgw-ceshi/#/',
    baseInfo: null,
    focus_picture: [],
    os: '',
    isWeiXin: '',
    tipModal: {
        status: 'hide',
        msg: '',
        type: 'success'
    },
    videoModal: {
      status: 'hide',
      url: ''
    },
    _tipModal: 'hide'
  },
  mutations: {
    saveBaseInfo(state, baseInfo) {
      state.baseInfo = baseInfo;
    },
    saveFocusPicture(state, focusPictureInfo) {
      state.focus_picture = focusPictureInfo
    },
    saveOs(state, os) {
      state.os = os;
    },
    saveIsWeiXin(state, isWeiXin) {
      state.isWeiXin = isWeiXin;
    },
    showTipModal(state, payload) {

      state.tipModal = {
        status: 'show',
        ...payload
      };
    },
    closeTipModal(state) {
      state.tipModal.status = 'hide';
    },
    handleVideoModal(state, payload) {
      state.videoModal = Object.assign({}, state.videoModal, payload);
    }
  },
  actions: {
    tipModalAct({ commit }, payload) {

      commit('showTipModal', payload);

      setTimeout(() => {
        commit('closeTipModal');
      }, 950);
    }
  },
  getters: {
    downloadUrl: state => {
      if(state.os == 'iOS') {
        if(!state.baseInfo.ios_download_url) {
            return 'javascript:alert("暂无ios版！");'
        }
        return state.baseInfo.ios_download_url;
      } else if (state.os == 'Android') {
        if(!state.baseInfo.android_download_url) {
            return 'javascript:alert("暂无Android版！");'
        }
        return state.baseInfo.android_download_url;
      } else {
        return 'javascript:alert("暂无此系统版游戏！");'
      }
    },
  }
});

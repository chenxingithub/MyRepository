import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    baseInfo: null,
    domain: 'https://www.310game.com',
    imgDomain: 'https://cdn3.ibingniao.com',
    mobile_url: 'https://www.310game.com/newcms2/ahzjz/#/',
    tipModal: {
      status: 'hide',
      msg: '',
      type: 'success'
    }
  },
  mutations: {
    saveBaseInfo(state, payload) {
      state.baseInfo = payload;
    },
    showTipModal(state, payload) {

      state.tipModal = {
        status: 'show',
        ...payload
      };
    },
    closeTipModal(state) {
      state.tipModal.status = 'hide';
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
    iosDownloadUrl: state => {
      if(!state.baseInfo) { return false; }

      if(!state.baseInfo.ios_download_url) {
          return 'javascript:alert("暂无ios版！");'
      }
      return state.baseInfo.ios_download_url + '?type=ios';
    },
    androidDownloadUrl: state => {
      if(!state.baseInfo) { return false; }

      if(!state.baseInfo.android_download_url) {
        return 'javascript:alert("暂无Android版！");';
      }
      return state.baseInfo.android_download_url + '?type=android';
    }
}
});

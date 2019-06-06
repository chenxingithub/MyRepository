import Vue from 'vue';
import Vuex from 'vuex';


let authorize_code="0568vPCNtUUkd8bl-w2St6w4mQfPxO4Ic1vnQDGW-9ONK6gYaOAcO7bs4wuOEuOhyYI62gV6BrjOIirK2s3Pu12TA"
let user_id = 'WaynfvbbMw==';
let user_name = 'bn0056';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        domain: 'https://www.icebirdgame.com',
        imgDomain: 'https://cdn3.ibingniao.com',
        baseInfo: null,
        // localImgUrl: './',//本地图片路径(./),上线替换成(./newcms2/310game-pc/)
        localImgUrl: './newcms2/310game-pc/',
        userInfo: null,
        AuthInfo: {
          access_token: '',
          user_name: '',
          user_id: ''
        },
        focus_picture: [],
        os: '',
        isWeiXin: '',
        tipModal: {
            status: 'hide',
            msg: '提示信息！',
            type: 'success'
        },
        _tipModal: 'hide',
        logRegModalType:  '', //login/register/modifyPwd /''
        autoLogin: true, //自动登录
    },
    mutations: {
        saveBaseInfo(state, baseInfo) {
            state.baseInfo = baseInfo;
        },
        saveAuthInfo(state, info) {
          state.AuthInfo = Object.assign({}, state.AuthInfo, info);
        },
        saveUserInfo(state, userInfo) {
          state.userInfo = Object.assign({},state.userInfo, userInfo);
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
        exchangeLogRegModal(state, payload) {//登录注册框切换关闭
          state.logRegModalType = payload;
        },
        handleLoginAuto(state, payload) {//是否自动登录
          state.autoLogin = payload;
        },
        handleLogout(state) {
          state.AuthInfo = {
            authorize_code: '',
            user_name: '',
            user_id: ''
          };
          // this.storejs.set('user',{
          //   authorize_code: '',
          //   user_name: '',
          //   user_id: ''
          // });
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
        // service_qq: state => {
        //     return state.baseInfo.service_qq.split(',');
        // },
        // service_phone: state => {
        //     return state.baseInfo.service_phone.split(',');
        // }
    }
});

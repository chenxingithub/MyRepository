export default {
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
    showTipModal(state, msg) {
        state.tipModal.status = 'show';
        // state.tipModal.msg = msg;
    },
    closeTipModal(state) {
        state.tipModal = 'hide';
    }
}
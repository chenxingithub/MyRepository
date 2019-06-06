export default {
    tipModalAct({ commit }, msg) {
        console.log('111');
        commit('showTipModal', msg);   
        
        setTimeout(() => {
            commit('closeTipModal');
        }, 800);
    },
    showModal({ commit }) {
        commit('showTipModal');   
    }
}
export default {
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
    service_qq: state => {
        return state.baseInfo.service_qq.split(',');
    },
    service_phone: state => {
        return state.baseInfo.service_phone.split(',');
    }
}
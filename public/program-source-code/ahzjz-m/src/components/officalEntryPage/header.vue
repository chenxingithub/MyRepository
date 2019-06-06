<template>
    <div id="entry-header" class="flex-sb-c" v-if="baseInfo">
        <div class="flex">
            <div class="icon" :style="{backgroundImage: 'url('+ domain+'/'+baseInfo.logo_url +')'}"></div>
            <div class="offical-title">
                <p class="offic-tit">暗黑终结者</p>
                <p class="title-dec">暗金装备按斤爆</p>
            </div>
        </div>
        <div class="head-nav flex">
            <a :href="downloadUrl" v-if="!isWeiXin" class="head-download-btn"></a>
            <a href="javascript:;" v-if="isWeiXin" 
            @click="toggleModal('open-browser')" class="head-download-btn"></a>
            <router-link to="/index" class="offical-btn"></router-link>
        </div>
        <click-tip-modal :modalType="clickTipModal_type" @closeModal="toggleModal"></click-tip-modal>
    </div>
</template>
<script>
import { mapState, mapGetters } from 'vuex'
import { isWeiXin, judgeClientOs } from '../../utils/index.js'
import clickTipModal from '../modal/index'


export default {
    name: "entryHeader",
    components: {
        clickTipModal
    },
    data() {
        return {
            downloadLink: '',
            clickTipModal_type: ''
        }
    },
    methods: {
        toggleModal(param) {
            this. clickTipModal_type = param;
        }
    },
    computed: {
       ...mapState([
           'baseInfo',
           'domain',
           'os',
           'isWeiXin'
       ]),
       ...mapGetters([
           'downloadUrl'
       ])
    },
}
</script>
<style lang="scss" scoped>
#entry-header{
    width: 750px;
    height: 100px;
    background: #330a00;
    position: fixed;
    top: 0;
    left: 50%;
    margin-left: -375px;
    z-index: 999;
    padding: 0 20px;
    .icon{
        width: 91px;
        height: 91px;
        // background-image: url('../../assets/icon.png');
        background-size: 100% 100%;
        background-repeat: no-repeat;
    }
    .offical-title{
        color: #ffffff;
        margin-left: 23px;
        .offic-tit{
            font-size: 32px;
        }
        .title-dec{
            margin-top: 5px;
            font-size: 22px;
            font-size: #e4dedd;
        }
    }
    .head-nav{
        &>a{
            display: block;
            text-decoration: none;
        }
        .head-download-btn{
            width: 130px;
            height: 67px;
            background: url('../../assets/head-btn.png') no-repeat;
            background-size: 287px 67px;
            margin-right: 21px;
        }
        .offical-btn{
            width: 130px;
            height: 67px;
            background: url('../../assets/head-btn.png') no-repeat;
            background-size: 287px 67px;
            background-position: -148px 0;
        }
    }
}
</style>

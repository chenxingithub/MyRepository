<template>
    <div id="header" class="flex-sb-c" v-if="this.$store.state.baseInfo">
        <div class="flex">
            <div class="icon" :style="{backgroundImage: 'url('+domain+ '/' +baseInfo.logo_url+')'}"></div>
            <div class="offical-title">
                <p class="offic-tit">暗黑终结者</p>
                <p class="title-dec">暗金装备按斤爆</p>
            </div>
        </div>
        <div class="head-nav flex">
            <a :href="downloadUrl" v-if="!isWeiXin" class="head-download-btn"></a>
            <a href="javascript:;" v-if="isWeiXin"
            @click="toggleModal('open-browser')" class="head-download-btn"></a>
        </div>
        <click-tip-modal :modalType="clickTipModal_type" @closeModal="toggleModal"></click-tip-modal>
    </div>
</template>
<script>
import { isWeiXin } from '../../utils/index.js'
import { mapState, mapGetters } from 'vuex'
import clickTipModal from '../modal/index'

export default {
    name: "Header",
    props:{
        logoUrl: String
    },
    components: {
        clickTipModal
    },
    data() {
        return {
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
            'isWeiXin'
        ]),
        ...mapGetters([
            'downloadUrl'
        ])
    }
}
</script>
<style lang="scss" scoped>
#header{
    width: 750px;
    height: 100px;
    background: #080409;
    position: fixed;
    top: 0;
    left: 50%;
    margin-left: -375px;
    padding: 0 20px;
    z-index: 1111;
    .icon{
        width: 91px;
        height: 91px;
        background-image: url('../../assets/icon.png');
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
            width: 254px;
            height: 76px;
            background: url('../../assets/downloadGameBtn.png') no-repeat;
            background-size: 254px 76px;
            margin-right: 21px;
        }
    }
}
</style>

<template>
    <div id="h-bottom" v-if="baseInfo">
        <!-- <a href="javascript:;" class="beganToFlaght"></a> -->

        <a :href="downloadUrl" v-if="!isWeiXin" class="beganToFlaght"></a>
            <a href="javascript:;" v-if="isWeiXin"
            @click="toggleModal('open-browser')" class="beganToFlaght"></a>

        <div class="bottom-nav flex-sb-c">
            <a href="javascript:;">
                <div class="offical-account flex" @click="changeType('wx-code')">
                    <img src="../../assets/wx-icon.png" alt="offical-account">
                    <span>关注公众号</span>
                </div>
            </a>
           <a href="javascript:;">
                <div class="service flex">
                    <img src="../../assets/service-icon.png" alt="service">
                    <router-link tag="span" to="/service">联系客服</router-link>
                </div>
           </a>
        </div>
        <div class="bottom-dec">
            <p>广州冰鸟网络科技有限公司 粤ICP备 16094894 号-3</p>
            <p>COPYRIGHT @2016 ALLRIGHTS RESERVED</p>
            <p>地址：广州市天河区天河路490号壬丰大厦4019</p>
            <p>联系电话：020-38915471 客服电话：020-38915471</p>
        </div>
        <a href="javascript:;" class="to-top" @click="scrollTop"></a>
        <click-tip-modal :modalType="clickTipModal_type" @closeModal="toggleModal"></click-tip-modal>
    </div>
</template>
<script>
import { scrollToTop } from '../../utils/index.js';
import { mapState, mapGetters } from 'vuex'
import clickTipModal from '../modal/index'

export default {
    name: 'bottom',
    components: {
        clickTipModal
    },
    data() {
        return {
            clickTipModal_type: ''
        }
    },
    methods: {
        changeType(type) {
        
            this.$emit('changeType', type || '');
        },
        scrollTop() {

            scrollToTop();
        },
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
<style lang="scss">
#h-bottom{
    position: relative;
    height: 900px;
    width: 750px;
    background: url('../../assets/footer-bg.jpg') no-repeat;
    background-size: 100% 100%;
    padding-top: 100px;
    .beganToFlaght{
        display: block;
        margin: 20px auto 0;
        width: 589px;
        height: 225px;
        background: url('../../assets/BeganToFight.png') no-repeat;
        background-size: 100% 100%;
    }
    .bottom-nav{
        width: 750px;
        height: 50px;
        margin: 170px auto 0;
        padding: 0 60px;
        img{
            width: 50px;
            height: 50px;
        }
        span{
            color: #ffffff;
            font-size: 32px;
            margin-left: 15px;
        }
    }
    .bottom-dec{
        position: absolute;
        bottom: 0;
        left: 0;
        width: 750px;
        height: 295px;
        font-size: 24px;
        border-top: 1px solid #72727b;
        color: #84848d;
        padding: 45px 28px 0;
        line-height: 40px;
        text-align: left;
    }
    .to-top{
        display: block;
        position: absolute;
        bottom: 120px;
        right: 25px;
        width: 93px;
        height: 93px;
        background: url('../../assets/to-top-icon.png') no-repeat;
        background-size: 100% 100%;
    }
}
</style>

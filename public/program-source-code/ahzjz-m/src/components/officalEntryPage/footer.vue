<template>
    <div id="enrty-footer">
        <div class="footer-nav flex-sb-c">
            <a href="javascript:;" class="offical-wx flex" @click="show_modal('wx-code')">
                <span></span>
                关注公众号
            </a>
            <router-link to="/index" class="ofical-website flex">
                <span></span>
                进入官网
            </router-link>
            <a href="javascript:;" class="share flex" @click="show_modal('share-friend')">
                <span></span>
                分享小伙伴
            </a>
        </div>
        <div class="footer-info">
            <p>广州冰鸟网络科技有限公司 粤ICP备 16094894 号-3</p>
            <p>COPYRIGHT @2016 ALLRIGHTS RESERVED</p>
            <p>地址：广州市天河区天河路490号壬丰大厦4019</p>
            <p>联系电话：020-38915471 客服电话：020-38915471</p>
        </div>
        <a href="javascript:;" class="footer-to-top" @click="scrollTop"></a>

        <tip-modal :modalType="modalType" @closeModal="close_modal"></tip-modal>
    </div>
</template>
<script>
import tipModal from '../../components/modal/index';
import { scrollToTop } from '../../utils/index.js';

export default {
    name: 'entryFooter',
    components: {
        tipModal
    },
    data() {
        return {
            modalType: ''
        }
    },
    methods: {
        show_modal(type) {
            this.modalType = type;
        },
        close_modal() {
            this.modalType = '';
        },
        scrollTop() {

            scrollToTop();
        }
    }
}
</script>
<style lang="scss">
#enrty-footer{
    position: relative;
    height: 805px;
    width: 750px;
    background: url('../../assets/entry-footer-bg.jpg') no-repeat;
    background-size: 100% 100%;
    padding: 100px 20px 0;
    .footer-nav{
        >a{
            // display: block;
            // width: 235px;
            height: 50px;
            color: #a37a4a;
            font-size: 30px;
            text-decoration: none;
        }
        span{
            display: block;
            width: 50px;
            height: 50px;
            margin-right: 12px;
        }
        .offical-wx{
            span{
                background: url('../../assets/func-icon.png') no-repeat;
                background-size: 154px 50px;
                background-position: -1px 0;
            }
        }
        .ofical-website{
            span{
                background: url('../../assets/func-icon.png') no-repeat;
                background-size: 154px 50px;
                background-position: -52px 0;
            }
        }
        .share{
            span{
                background: url('../../assets/func-icon.png') no-repeat;
                background-size: 153px 50px;
                background-position: -101px 0;
            }
        }
    }
    .footer-info{
        margin-top: 58px;
        font-size: 24px;
        color: #aaaaaa;
        line-height: 45px;
        border-top: 2px solid #d19f5e;
        padding-top: 60px;
    }
    .footer-to-top{
        display: block;
        text-decoration: none;
        position: absolute;
        top: 310px;
        right: 20px;
        width: 93px;
        height: 93px;
        background: url('../../assets/footer-to-top.png') no-repeat;
        background-size: 100% 100%;
    }
}
</style>

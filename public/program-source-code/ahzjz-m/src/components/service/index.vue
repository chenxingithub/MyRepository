<template>
    <div class="service">
        <Header></Header>
        <div class="service-head"></div>
        <div class="service-content" v-if="this.$store.state.baseInfo">
            <a href="javascript:;" class="tag-read" type="text" 
            :data-clipboard-text="item.replace(/[^0-9]/ig,'')" @click="copy"
            v-for="(item, index) in service_qq.split(',')" :key="index+1">
                <div class="qq">
                    <span class="qq-icon"></span>
                    <span>{{ item }}</span>
                </div>
            </a>
            <!-- <a href="javascript:;">
                <div class="qq">
                    <span class="qq-icon"></span>
                    <span>4598569213</span>
                </div>
            </a> -->
            <a :href="'tel:'+item"  v-for="(item, index) in service_phone.split(',')" :key="index">
                <div class="tel">
                    <span class="tel-icon"></span>
                    <span>{{ item }}</span>
                </div>
            </a>
        </div>
        <tip-message></tip-message>
        <Footer-nav></Footer-nav>
    </div>
</template>
<script>
import Header from '../header/header';
import FooterNav from '../footer/footer';
import { mapGetters } from 'vuex';
import  Clipboard  from "clipboard";
import { tipMessage } from '../../utils/index.js'

export  default {
    name: 'service',
    components: {
        Header,
        FooterNav,
        tipMessage
    },
    computed: {
        service_phone() {
            return this.$store.state.baseInfo.service_phone;
        },
        service_qq() {
            return this.$store.state.baseInfo.service_qq;
        }
    },
    methods: {
        copy() {
            let clipboard = new Clipboard('.tag-read');
            let text = null, _this = this;
            clipboard.on('success', e => {
                // 释放内存
                text = '已复制Q号到剪贴板';
                _this.$store.dispatch('tipModalAct',{msg: text, type: 'success'});
                clipboard.destroy()
            })
            clipboard.on('error', e => {
                // 不支持复制
                text = '该浏览器不支持自动复制';
                _this.$store.dispatch('tipModalAct',{msg: text, type: 'fail'});
                // 释放内存
                clipboard.destroy()
            })

        }
    }
}
</script>
<style lang="scss" scoped>
.service{
    width: 750px;
    padding: 100px 0;
    min-height: 100vh;
    background: #1f1625;
}
.service-head{
    width: 750px;
    height: 280px;
    background: url('../../assets/service-head-bg.png') no-repeat;
    background-size: 100% 100%;
}
.service-content{
    width: 750px;
    text-align: center;
    padding-top: 50px;
    font-size: 36px;
    >a{
        display: block;
        text-decoration: none;
        color: #fff2e3;
        width: 528px;
        height: 128px;
        margin: 0 auto 24px;
    }
    @mixin service-item {
        width: 528px;
        height: 128px;
        background: url('../../assets/service-bg.png') no-repeat;
        background-size: 100% 100%;
        padding-left: 90px;
        display: flex;
        align-items: center;
    }
    @mixin service-icon{
        display: inline-block;
        width: 48px;
        height: 52px;
        margin-right: 30px;
    }
    .qq{
       @include service-item;
    
        .qq-icon{
            @include service-icon;
            background: url('../../assets/qq.png') no-repeat;
            background-size: 100% 100%;      
        }
    }
    .tel{
        @include service-item;

        .tel-icon{
            @include service-icon;
            background: url('../../assets/tel.png') no-repeat;
            background-size: 100% 100%;
         }
    }
}

</style>

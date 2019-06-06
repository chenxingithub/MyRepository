<template>
    <div id="recommend">
        <div class="recommend-head" v-if="baseInfo">
            <div class="recommend-qq flex-sb-c">
                <div class="recommend-copy-desc">
                    <p>{{ baseInfo.qq_group_introduce.split('\n')[0] }}</p>
                    <p>{{ baseInfo.qq_group_introduce.split('\n')[1] }}</p>
                </div>
                <button class="recommend-qq-btn copy-tag-read" 
                  @click="copy('qq',baseInfo.qq_group_num)">
                 复制Q群号
                </button>
            </div>
            <div class="recommend-wx flex-sb-c">
                <div class="recommend-copy-desc">
                    <p>{{ baseInfo.wechat_introduce.split('\n')[0] }}</p>
                    <p>{{ baseInfo.wechat_introduce.split('\n')[1] }}</p>
                </div>
                <a href="javascript:;" class="recommend-wx-btn copy-tag-read"
                  @click="copy('wx',baseInfo.wechat_num)">
                 复制微信号
                </a>
            </div>
        </div>
        <div class="recommend-content flex-wrap-sb-c">
            <div class="recommend-list" v-for="(item, index) in recommendLists" :key="index" @click="triggerDialog(item.rule, 1)">
                <div :class="item.label == 1 ? 'rec-flag-new' : 'rec-flag-hot'"></div>
                <div class="recommend-list-icon">
                    <img :src="domain + '/' + item.img" alt="image">
                </div>
                <div class="recommend-list-title">
                    {{ item.title }}
                </div>
            </div>
        </div>
        <!-- 复制弹窗 -->
        <div id="recommend-modal" class="modal-cover" v-if="modal">
            <div class="modal-content">
                <div class="recommend-modal-qq" v-if="modal == 'qq'">
                    <a href="javascript:;" class="recommend-close" @click="copyModal('')"></a>
                    <a href="javascript:;" class="recommend-go" @click="copyModal('')"></a>

                </div>
                <div class="recommend-modal-wx" v-if="modal == 'wx'">
                    <a href="javascript:;" class="recommend-close" @click="copyModal('')"></a>
                    <a href="javascript:;" class="recommend-go" @click="copyModal('')"></a>
                    <div class="recommend-wx-code"></div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapState } from 'vuex';
import { judgeClientOs } from '../../utils/utils.js'
import { ruleComponentReq, keywordReq, baseInfoReq } from '../../api/request.js'

export default {
    name: 'recommend',
    data() {
        return {
            recommendLists: null,
            baseInfo: null,
            modal: ''
        }
    },
    methods: {
        requestHotspotcont() {//请求板块内容
            let _this = this;

            ruleComponentReq(this.baseConf[1].id)
                .then(function(res) {

                    _this.recommendLists = res.data.data;
                });
        },
        copyModal(type) {
            this.modal = type;
        },
        closeCopyModal() {
            this.modal = '';
        },
        copy(type, msg) {
            let os = judgeClientOs();

            if(os == 'iOS') {
                window.webkit.messageHandlers.iOS.postMessage({functionName:'copy', parameter: msg});
            }
            if(os == 'Android') {
                android.copyText(msg);
            }
            this.copyModal(type);

        },
        triggerDialog(keyword, type) {
            let _this = this;

            _this.$router.push({path: 'dialog'});
            _this.$store.commit('pushDialogList', {type: 'user', msg: keyword})

            keywordReq(keyword, type, this.urlParams).then((res) => {

                let data = res.data.data.content;
                _this.$store.commit('pushDialogList', {type: 'servicer', msg: data});
            })
        }
    },
     computed: {
        ...mapState([
            'baseConf',
            'domain',
            'dialogList',
            'urlParams'
        ]),
    },
    created() {
        let _this = this;
        if(this.baseConf) {
            this.requestHotspotcont();
        }
        baseInfoReq(this.urlParams.game_id).then(function(res) {

                _this.baseInfo = res.data.data;
            });
        
    },
    watch: {
        'baseConf': 'requestHotspotcont'
    }
}
</script>
<style lang="scss">
#recommend{
    width: 900px;
    height: 1020px;
    padding-top: 30px;
    .recommend-head{
        width: 864px;
        height: 310px;
        background: url('../../assets/recommend-head-bg.png') no-repeat;
        background-size: 100% 100%;
        margin: 0 auto;
        padding-top: 24px;
        @mixin recommend-desc {
            width: 830px;
            height: 124px;
            padding: 0 25px 0 100px;
            p:nth-child(2){
                margin-left: 108px;
            }
        }
        @mixin copyBtn {
            display: block;
            width: 220px;
            height: 78px;
            line-height: 78px;
            background: url('../../assets/copy-btn.png') no-repeat;
            background-size: 100% 100%;
            text-align: center;
            font-size: 30px;
            color: #ffffff;
            text-decoration: none;
        }
        .recommend-qq{
            @include recommend-desc;
            margin: 0 auto 14px;
            background: url('../../assets/recommend-head-qq.png') no-repeat;
            background-size: 100% 100%;
        }
        .recommend-qq-btn{
            @include copyBtn;
        }
        .recommend-wx{
            @include recommend-desc;
            margin: 0 auto;
            background: url('../../assets/recommend-head-wx.png') no-repeat;
            background-size: 100% 100%;
        }
        .recommend-wx-btn{
            @include copyBtn;
        }
        .recommend-copy-desc{
            font-size: 34px;
            color: #ffffff;
        }
    }
    .recommend-content{
        height: 640px;
        margin-bottom: 120px;
        padding: 70px 30px 0;
        overflow: scroll;
        .recommend-list{
            position: relative;
            width: 258px;
            height: 257px;
            margin: 12px 0;
            background: url('../../assets/recommend-list-bg.png') no-repeat;
            background-size: 100% 100%;
            text-align: center;
        }
        @mixin recommend-flag {
            position: absolute;
            top: 0;
            right: 0;
            width: 86px;
            height: 87px;
        }
        .rec-flag-new{
            @include recommend-flag;
            background: url('../../assets/new-flag-icon.png') no-repeat;
            background-size: 100% 100%;
        }
        .rec-flag-hot{
            @include recommend-flag;
            background: url('../../assets/hot-flag-icon.png') no-repeat;
            background-size: 100% 100%;
        }
        .recommend-list-icon{
            padding-top: 15px;
            width: 157px;
            height: 157px;
            margin: 12px auto;
            img{
                width: 157px;
                height: 154px;
            }
        }
        .recommend-list-title{
            font-size: 40px;
            color: #1f1208;
            font-weight: 600;
        }
    }
}
#recommend-modal{
    @mixin close {
        width: 71px;
        height: 70px;
        position: absolute;
        display: block;
        background: url('../../assets/recommend-modal-close.png') no-repeat;
        background-size: 100% 100%;
    }
    @mixin go {
        width: 173px;
        height: 75px;
        position: absolute;
        display: block;
        background: url('../../assets/recommend-modal-go.png') no-repeat;
        background-size: 100% 100%;
    }
    .recommend-modal-qq{
        width: 801px;
        height: 637px;
        background: url('../../assets/recommend-qq-modal.png') no-repeat;
        background-size: 100% 100%;
        .recommend-close{
            @include close;
            top: 0;
            right: -20px;
        }
        .recommend-go{
           @include go;
            top: 430px;
            left: 390px;
        }
    }
    .recommend-modal-wx{
        width: 742px;
        height: 592px;
        background: url('../../assets/recommend-wx-modal.png') no-repeat;
        background-size: 100% 100%;
        .recommend-close{
            @include close;
            top: -15px;
            right: 10px;
        }
        .recommend-go{
           @include go;
            top: 360px;
            left: 270px;
        }
        .recommend-wx-code{
            width: 180px;
            height: 180px;
            background: url('../../assets/wx-code.png') no-repeat;
            background-size: 100% 100%;
            position: absolute;
            top: 250px;
            left: 68px;
        }
    }
}
</style>

<template>
    <div id="feedback">
        <div class="feedback-box">
            <div class="feedback-content" ref="feedbackScroll">
                <div class="content">
                    <!-- <div class="userItem">
                        <div class="userc-icon"></div>
                        <div class="user-msg-box">
                            啦啦啦啦啦啦啦啦啦啦啦
                        </div>
                    </div> -->
                    <div v-for="(item, index) in feedBackList" :key="index">
                        <div class="userItem" v-if="item.type == 'user'" ><!-- 用户信息 -->
                            <div class="userc-icon"></div>
                            <div class="user-msg-box">
                                {{ item.msg }}
                            </div>
                        </div>
                        <div class="servicerItem" v-if="item.type == 'servicer'"><!-- 客服 -->
                            <div class="servicer-msg-box">
                                <div class="servicer-msg-text" v-html="item.msg">
                                    
                                </div>
                            </div>
                            <div class="servicer-icon"></div>
                        </div>
                    </div>
                    <!-- <div class="servicerItem" v-if="feedbackTip">
                        <div class="servicer-msg-box">
                            <div class="servicer-msg-text">
                                <span>亲爱的训练师：</span>
                                <p style="text-indent: 2em">如果您对游戏什么建议，可以及时反馈给林秘书，林秘书会合同工作人员好好交流反馈的~</p>
                                <p style="text-indent: 2em">如果您的建议被采纳了，会有神秘大奖的哦，么么哒~</p>
                                <br><br>
                                <span style="color: #FF9900;">建议反馈格式：</span>
                                <p style="text-indent: 2em">在下方输入框内直接输入您的建议即可，内容越详尽，被采纳的几率越大哦~</p>
                            </div>
                        </div>
                        <div class="servicer-icon"></div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { feedbackReplyQueryReq } from '../../api/request.js'
import { mapState, mapMutations } from 'vuex'
import Bscroll from 'better-scroll'
 
export default {
    name: 'feedback',
    methods: {
        ...mapMutations([
            'pushFeedBackList',
            'feedBackHistory',
            'feedbackTipMut'
        ]),
       
        queryFeedbackHistory() {
            let _this = this;
            // alert('feedBack请求1');
            feedbackReplyQueryReq(this.urlParams).then((res) => {

                
                let data = res.data.data.data;
                let lists = [];
                // alert(JSON.stringify(data));
                if(Array.isArray(data)) {
                    for(let item of data) {
                        if(item.from == 'admin') {
                            lists.push({type: 'servicer', msg: item.feedback_message});
                        } else {
                            lists.push({type: 'user', msg: item.feedback_message});
                        }
                    }
                    if(this.feedbackTip) {//每次打开游戏精灵时提示一次
                        let servicerTip = `<span>亲爱的训练师：</span>
                                <p style="text-indent: 2em">如果您对游戏什么建议，可以及时反馈给林秘书，林秘书会合同工作人员好好交流反馈的~</p>
                                <p style="text-indent: 2em">如果您的建议被采纳了，会有神秘大奖的哦，么么哒~</p>
                                <br><br>
                                <span style="color: #FF9900;">建议反馈格式：</span>
                                <p style="text-indent: 2em">在下方输入框内直接输入您的建议即可，内容越详尽，被采纳的几率越大哦~</p>`;
                        lists.unshift({type: 'servicer', msg: servicerTip});

                        this.feedbackTipMut();
                    }
                    this.feedBackHistory(lists.reverse());
                }
                // _this.pushFeedBackList({type: 'servicer', msg: res.data.message});
            });
        },
        feedbackToTop() {
            if(this.scroll) {
                this.scroll.scrollTo(0, this.scroll.minScrollY, 600);
            }
        },
        feedbackToBottom() {
            if(this.scroll) {
                let _this = this;
                setTimeout(() => {
                    _this.scroll.refresh();
                    _this.scroll.scrollTo(0, _this.scroll.maxScrollY, 600);
                }, 60);
            }
        }
    },
    computed: {
        ...mapState([
            'dialogToTop',
            'feedBackList',
            'urlParams',
            'feedbackTip'
        ])
    },
    created() {
        // alert('触发feedback created');
        if(this.feedbackTip) {
            this.queryFeedbackHistory();
        }
        
    },
    mounted() {
        
        let _this = this;

        this.$nextTick(() => {
            _this.scroll = new Bscroll(this.$refs.feedbackScroll, {click: true});

            this.feedbackToBottom();
        });
    },
    watch: {
        'dialogToTop': 'feedbackToTop',
        'feedBackList': 'feedbackToBottom'
    }
}
</script>
<style lang="scss">
#feedback{
    width: 900px;
    height: 1020px;
    padding: 30px 16px 0;
    .feedback-box{
        width: 868px;
        height: 986px;
        margin: 0 auto;
        background: url('../../assets/dialog-bg.png') no-repeat;
        background-size: 868px 986px;
        overflow: scroll;
        padding-top: 9px;
    }
    .feedback-content{
        padding-top: 20px;
        height: 969px;
        overflow: scroll;
        // background: skyblue;
    }
    .userItem{
        display: flex;
        .userc-icon{
            width: 105px;
            height: 105px;
            background: url('../../assets/user-dialog-icon.png') no-repeat;
            background-size: 100% 100%;
            flex-shrink: 0;
            margin: 0 8px;
        }
        .user-msg-box{
            margin: 50px 0 0 0;
            padding: 30px 40px;
            background: #5b82a3;
            display: inline-block;
            height: auto;
            border-radius: 18px;
            color: #ffffff;
            font-size: 36px;
            max-width: 620px;
            flex-shrink: 0;
        }
    }
    .servicerItem{
        display: flex;
        justify-content: flex-end;
        .servicer-icon{
            width: 105px;
            height: 105px;
            background: url('../../assets/servicer-dialog-icon.png') no-repeat;
            background-size: 100% 100%;
            flex-shrink: 0;
            margin: 0 10px;
        }
        .servicer-msg-box{
            margin: 50px 0 0 125px;
            padding: 30px 0;
            background: #5b82a3;
            display: inline-block;
            height: auto;
            border-radius: 18px;
            color: #ffffff;
            font-size: 36px;
            max-width: 620px;
            flex-shrink: 0;
        }
        .servicer-msg-text{
            padding: 0 40px;
        }
        .servicer-msg-border{
            width: 620px;
            height: 55px;
            background: url('../../assets/feedback-bar.png') no-repeat;
            background-size: 100% 100%;
        }
        .servicer-feedback{
            padding: 0 20px;
            height: 60px;
            span{
                color: #d3d3d3;
                font-size: 24px;
                margin-right: 10px;
            }
        }
        @mixin resolveBtn{
            display: block;
            text-decoration: none;
            width: 139px;
            height: 58px;
            background: url('../../assets/feedback-resolve-btn.png') no-repeat;
            background-size: 277px 58px;
        }
        .feedback-resolve-btn{
            @include resolveBtn;
            background-position: 0 0;
        }
        .feedback-unsolved-btn{
            @include resolveBtn;
            background-position: -139px 0;
        }
    }
}
</style>

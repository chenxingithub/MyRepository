<template>
    <div id="dialog-box">
        <div class="dialogBox">
            <div class="dialog-content" ref="dialogContent">
                <div class="content" @click="triggerKeyword">
                    <div v-for="(item, index) in dialogListAddQuestionState" :key="index">
                        <div class="userItem" v-if="item.type == 'user'">
                            <div class="userc-icon"></div>
                            <div class="user-msg-box">
                                {{ item.msg }}
                            </div>
                        </div>

                        <div class="servicerItem" v-if="item.type == 'servicer'">
                            <div class="servicer-msg-box">
                                <div class="servicer-msg-text" v-html="item.msg">
                                    <!-- {{  }} -->
                                </div>
                                <div class="servicer-msg-border"></div>
                                <div class="servicer-dialog flex" v-if="!item.resolveState">
                                    <span>此回答是否解决了你的问题</span>
                                    <a href="javascript:;" class="dialog-resolve-btn" @click="answerFeedback(index, 'resolve')"></a>
                                    <a href="javascript:;" class="dialog-unsolved-btn" @click="answerFeedback(index, 'unsolved')"></a>
                                </div>
                                <div class="servicer-dialog" style="text-align: center;" v-if="item.resolveState">
                                    <span>感谢您的反馈</span>
                                </div>
                            </div>
                            <div class="servicer-icon"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapState, mapActions } from 'vuex'
import Bscroll from 'better-scroll'
import { keywordReq } from '../../api/request.js'
import { matchKeyWord } from '../../utils/utils.js'

export default {
    name: 'dialogBox',
    computed: {
        ...mapState([
            'dialogList',
            'dialogToTop',
            'urlParams'
        ]),
        dialogListAddQuestionState() {//原始返回数据新增 resolveState属性
            for (let item of this.dialogList) {

                if(item.hasOwnProperty('resolveState')) {
                    return this.dialogList;
                }
                item.resolveState = '';
            }

            return this.dialogList;
        }
        
    },
    methods: {
        ...mapActions([
            'syncPushDialogList'
        ]),
        scrollToBottom() {
            let _this = this;
            
            if(this.scroll) {
                let timeout = setTimeout(() => {//dom渲染结束后滚动到底部
                    _this.scroll.refresh();//新增数据，重新计算better-scroll
                    _this.scroll.scrollTo(0,_this.scroll.maxScrollY, 600);
                }, 100);
            }
        },
        answerFeedback(index, type) {

            let arr = this.dialogList[index];

            this.dialogList.splice(index,1,{msg: arr.msg, resolveState: type, type: arr.type});
            // 使用属性直接复制无法触发视图更新，故使用splice(vue 重定义过)
        },
        dialogListToTop() {
            if(this.scroll) {
                this.scroll.scrollTo(0, this.scroll.minScrollY, 600);
            }
        },
        triggerKeyword(e) {
            
            if(e.target.className == "bn-tag-keyword") {
                
                let _this = this;
                keywordReq(e.target.innerText, 2, this.urlParams).then((res) => {
                    
                    let data = matchKeyWord(res.data.data.content);
                    _this.syncPushDialogList({type: 'servicer', msg: data});
                });
            } else {
                return false;
            }
        }
    },

    mounted() {
        let _this = this;

        this.$nextTick(() => {
            _this.scroll = new Bscroll(this.$refs.dialogContent, {click: true});
            if(this.$route.query.type == 'history') {
                return false;
            }
            let timeout = setTimeout(() => {//dom渲染结束后滚动到底部
                    _this.scroll.scrollTo(0,_this.scroll.maxScrollY, 600);
            }, 500);
        })
        
    },
    watch: {
        'dialogList': 'scrollToBottom',
        'dialogToTop': 'dialogListToTop'
    }
}
</script>
<style lang="scss">
#dialog-box{
    width: 900px;
    height: 1020px;
    padding: 30px 16px 0;
    .dialogBox{
        width: 868px;
        height: 986px;
        margin: 0 auto;
        background: url('../../assets/dialog-bg.png') no-repeat;
        background-size: 868px 986px;
        overflow: scroll;
        padding-top: 9px;
    }
    .dialog-content{
        padding-top: 20px;
        height: 969px;
        overflow: auto;
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
        .servicer-dialog{
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
        .dialog-resolve-btn{
            @include resolveBtn;
            background-position: 0 0;
        }
        .dialog-unsolved-btn{
            @include resolveBtn;
            background-position: -139px 0;
        }
    }
}
</style>


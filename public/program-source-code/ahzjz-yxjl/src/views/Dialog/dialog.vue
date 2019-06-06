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
                                <div class="ql-container ql-snow">
                                    <div class="servicer-msg-text ql-editor" v-html="item.msg">
                                    
                                    </div>
                                
                                <div class="servicer-msg-border">
                                    
                                </div>
                                <div class="servicer-dialog flex-r-c" v-if="!item.resolveState">
                                    <span>此回答是否解决了您的问题</span>
                                    <a href="javascript:;" class="dialog-resolve-btn" @click="answerFeedback(index, 'resolve')">解决</a>
                                    <a href="javascript:;" class="dialog-unsolved-btn" @click="answerFeedback(index, 'unsolved')">未解决</a>
                                </div>
                                <div class="servicer-dialog" style="text-align: center;" v-if="item.resolveState">
                                    <span>感谢您的反馈</span>
                                </div>
                                </div>
                            </div>
                            <!-- <div class="servicer-icon"></div> -->
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="dialog-border-bar"></div>
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
                }, 160);
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
                    console.log(res);
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
    .ql-editor{
        height: auto;
    }
    .ql-container{
        font-size: inherit;
    }
    width: 825px;
    height: 1188px;
    margin: 0 auto;
    .dialogBox{
        width: 100%;
        height: 1188px;
        margin: 0 auto;
        // background: url('../../assets/dialog-bg.png') no-repeat;
        // background-size: 868px 986px;
        overflow: scroll;
        padding-top: 9px;
        line-height: 45px;
    }
    .dialog-content{
        padding-top: 20px;
        height: 1150px;
        overflow: auto;
    }
    .dialog-content img{
        max-width: 100%;
    }
    .dialog-border-bar{
        width: 809px;
        height: 3px;
        margin: 10px 0;
        background: url('../../assets/hs-border-bar.png') no-repeat;
        background-size: 100% 100%;
    }
    .userItem{
        display: flex;
        margin-bottom: 40px;
        .userc-icon{
            width: 105px;
            height: 105px;
            background: url('../../assets/user-dialog-icon.png') no-repeat;
            background-size: 100% 100%;
            flex-shrink: 0;
            margin: 0 10px 0 10px;
        }
        .user-msg-box{
            margin: 40px 0 0 0;
            padding: 30px 40px;
            background: #434343;
            display: inline-block;
            height: auto;
            border-radius: 18px;
            color: #ffffff;
            font-size: 36px;
            max-width: 680px;
            flex-shrink: 0;
            border: 1px solid #7d7d7d;/*no*/
        }
    }
    .servicerItem{
        display: flex;
        margin-bottom: 40px;
        .servicer-icon{
            width: 105px;
            height: 105px;
            background: url('../../assets/servicer-dialog-icon.png') no-repeat;
            background-size: 100% 100%;
            flex-shrink: 0;
            margin: 0 10px 0 10px;
        }
        .servicer-msg-box{
            margin: 40px 0 0 30px;
            padding: 30px 0;
            background: #434343;
            display: inline-block;
            height: auto;
            border-radius: 18px;
            color: #ffffff;
            font-size: 36px;
            width: 785px;
            flex-shrink: 0;
            border: 1px solid #7d7d7d;/*no*/
        }
        .servicer-msg-text{
            padding: 0 40px;
        }
        .servicer-msg-border{
            width: 86%;
            height: 1px; /*no*/
            background: #7d7d7d;
            margin: 35px auto;
            // background: url('../../assets/feedback-bar.png') no-repeat;
            // background-size: 100% 100%;
        }
        .servicer-dialog{
            padding: 0 60px;
            // text-align: center;
            height: 60px;
            flex-shrink: 0;
            span{
                color: #d3d3d3;
                font-size: 24px;
                margin-right: 60px;
            }
        }
        @mixin resolveBtn{
            display: block;
            text-decoration: none;
            width: 134px;
            height: 55px;
            background: url('../../assets/send-msg-btn.png') no-repeat;
            background-size: 149px 70px;
            color: #ffffff;
            text-align: center;
            line-height: 55px;
            font-size: 30px;
            font-weight: 600;
            flex-shrink: 0;
        }
        .dialog-resolve-btn{
            @include resolveBtn;
            margin-right: 10px;
            // background-position: 0 0;
        }
        .dialog-unsolved-btn{
            @include resolveBtn;
            // background-position: -139px 0;
        }
    }
}
</style>


<template>
    <div id="foot" class="flex-sb-c">
        <!-- <div class=""> -->
            
            <input type="text" class="msg-input" v-model="inputVal">
            <div class="history-btn" @click="historyRecord"></div>
            <a href="javascript:;" class="send-msg" @click="pushDialog">
                <span>{{ dialogType == 'dialog' ? '提 问' : '提建议' }}</span>
            </a>
        <!-- </div> -->
    </div>
</template>
<script>
import { keywordReq, feedbackSubReq } from '../../api/request.js'
import { mapState, mapActions, mapMutations } from 'vuex'
import { matchKeyWord } from '../../utils/utils.js'

export default {
    name: 'footNav',
    data() {
        return {
            inputVal: '',
            dialogType: 'dialog'
        }
    },
    computed:{
        ...mapState([
            'urlParams',
        ])
    },
    methods: {
         ...mapMutations([
             'pushDialogList',
             'pushFeedBackList',
             'dialogListToTop'
        ]),
        ...mapActions([
            'syncPushDialogList'
        ]),
        pushDialog() {
            let _this = this;
            let inputVal = this.inputVal.trim();
            if(this.$route.name == 'feedback') {
                // this.$router.push();
                if(inputVal) {
                    this.pushFeedBackList({type: 'user', msg: inputVal});
                    feedbackSubReq(inputVal,this.urlParams).then((res) => {
                        _this.inputVal = '';

                        this.pushFeedBackList({type: 'servicer', msg: res.data.message});
                    });
                }
                return false;
            }
            if(this.$route.name != 'dialog') {
                this.$router.push({path: 'dialog'})
            }
            if(inputVal) {
                // this.$store.commit('pushDialogList', {type: 'user', msg: this.inputVal});
                this.pushDialogList({type: 'user', msg: inputVal});
                keywordReq(this.inputVal, 1, this.urlParams).then((res) => {
                    let data = res.data.data;
                    _this.inputVal = '';

                    if(data) {
                        _this.pushDialogList({type: 'servicer', msg: matchKeyWord(data.content)});
                    } else {
                        _this.pushDialogList({type: 'servicer', msg: '大菠萝不太清楚您的意思。。。'})
                    }
                   
                });
            }
        },
        inputExchange() {
            if(this.$route.name == 'feedback') {
                this.dialogType = 'feedback';
            } else {
                this.dialogType = 'dialog';
            }
        },
        historyRecord() {
            this.dialogListToTop();
            
            if(this.$route.name == 'feedback') {
                this.$router.push({path: 'feedback', query: {type: 'history'}});
            } else {
                this.$router.push({path: 'dialog', query: {type: 'history'}});
            }
        }
    },
    mounted() {
        this.inputExchange();
    },
    watch: {
        '$route': 'inputExchange'
    }
}
</script>
<style lang="scss">
#foot{
    width: 825px;
    // padding: 0 29px;
    margin: 50px auto;
    .history-btn{
        width: 86px;
        height: 75px;
        background: url('../../assets/history-btn.png') no-repeat;
        background-size: 86px 75px;
        margin: 0 29px 0 39px;
        flex-shrink: 0;
    }
    .msg-input{
        width: 478px;
        height: 73px;
        border: 1px solid #7d7d7d; /*no*/
        padding: 0 15px;
        font-size: 30px;
        margin-top: -4px;
        background-color: rgba(255,255,255, 0);
        color: #ffffff;
        flex-shrink: 0;
        border-radius: 0;
        position: relative;
    }
    .send-msg{
        width: 183px;
        height: 75px;
        display: block;
        text-decoration: none;
        background: url('../../assets/send-msg-btn.png') no-repeat;
        background-size:183px 75px;
        text-align: center;
        flex-shrink: 0;
        span{
            line-height: 75px;
            font-size: 40px;
            font-weight: 600;
            color: #ffffff;
            // letter-spacing: .2em;
        }
    }
}
</style>

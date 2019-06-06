<template>
    <div id="foot" class="flex-sb-c">
        <!-- <div class=""> -->
            <div class="history-btn" @click="historyRecord"></div>
            <input type="text" class="msg-input" v-model="inputVal">
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
                        _this.pushDialogList({type: 'servicer', msg: '光线不好，没听清，请重新组织语言提问！'})
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
    width: 900px;
    padding: 0 29px;
    margin: 52px 0;
    .history-btn{
        width: 88px;
        height: 89px;
        background: url('../../assets/history-btn.png') no-repeat;
        background-size: 100% 100%;
    }
    .msg-input{
        width: 519px;
        height: 74px;
        border-top: 1px solid #8c9095; /*no*/
        border-bottom: 1px solid #babcbf; /*no*/
        padding: 0 15px;
        font-size: 30px;
        margin-top: -4px;
    }
    .send-msg{
        width: 201px;
        height: 85px;
        display: block;
        text-decoration: none;
        background: url('../../assets/send-msg-btn.png') no-repeat;
        background-size: 100% 100%;
        text-align: center;
        span{
            line-height: 85px;
            font-size: 40px;
            font-weight: 600;
            color: #722905;
            // letter-spacing: .2em;
        }
    }
}
</style>

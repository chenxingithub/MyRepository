<template>
  <div class="feedback-m feedback-wrapper">
    <div class="content click">
      <div v-for="(item, index) in feedBackList" :key="index">
        <div class="userItem click" v-if="item.type == 'user'" ><!-- 用户信息 -->
          <div class="user-msg-box">
            <div class="flag"></div>
            <div class="light-top"></div>
            <div class="text">{{ item.msg }}</div>
            <div class="light-bottom"></div>
          </div>
          <div class="userc-icon"></div>
        </div>
        <div class="servicerItem click" v-if="item.type == 'servicer'">
          <div class="servicer-msg-box fle">
            <div class="servicer-icon"></div>
            <div class="servicer-msg-text click">
              <div class="flag"></div>
              <div class="light-top"></div>
              <div class="text" v-html="item.msg"></div>
              <!-- <div></div> -->
              <div class="light-bottom"></div>
            </div>
          </div>
          <div class="wx-qq-box flex-sb-c"
            v-if="item.hasOwnProperty('contactBox') && baseInfo && baseInfo.channel_configure">
            <div class="wx flex-sb-c" v-if="baseInfo.channel_configure.wechat_num">
              <div class="text">
                <p>{{ baseInfo.channel_configure.wechat_introduce.split('|')[0] }}</p>
                <p>{{ baseInfo.channel_configure.wechat_introduce.split('|')[1] }}</p>
              </div>
              <a href="javascript:;" class="copy-btn click"
                @click="copy('wx', baseInfo.channel_configure.wechat_num)">前往</a>
            </div>
            <div class="qq flex-sb-c" v-if="baseInfo.channel_configure.qq_group_num">
              <div class="text">
                <p>{{ baseInfo.channel_configure.qq_group_introduce.split('|')[0] }}</p>
                <p>{{ baseInfo.channel_configure.qq_group_introduce.split('|')[1] }}</p>
              </div>
              <a href="javascript:;" class="copy-btn click"
                @click="copy('qq', baseInfo.channel_configure.qq_group_num)">前往</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="copyModal modal-cover"  v-if="modal">
      <div class="modal-content">

        <div class="copy-wx-modal" v-if="modal == 'wx'">
          <div class="close" @click="handleModal('')"></div>
          <div class="wx-qrcode"></div>
          <a href="javascript:;" class="close-copy-btn click" @click="handleModal('')">GO</a>
        </div>
        <div class="copy-qq-modal"  v-if="modal == 'qq'">
          <div class="close" @click="handleModal('')"></div>
          <a href="javascript:;" class="close-copy-btn click" @click="handleModal('')">GO</a>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import {
  feedbackReplyQueryReq,
  feedbackMsgStatusReported } from '../../api/index.js'
import { mapState, mapMutations } from 'vuex'
import Bscroll from 'better-scroll'
import { judgeClientOs } from '../../utils/utils.js'
  export default {
    name: 'feedback',
    data() {
      return {
        modal: '',//wx ,qq, ''
      }
    },
    methods: {
      ...mapMutations([
        'pushFeedBackList',
        'feedBackHistory',
        'feedbackTipMut',
        'handleFeedBackMsgStatus'
      ]),
      queryFeedbackHistory() {
        let _this = this;
        // alert('feedBack请求1');
        feedbackReplyQueryReq(this.urlParams).then((res) => {
          let data = res.data.data.data;
          let lists = [];
          // alert(JSON.stringify(data));

          if (Array.isArray(data)) {
            for (let item of data) {
              if (item.from == 'admin') {
                lists.push({
                  type: 'servicer',
                  msg: item.feedback_message
                });
              } else {
                lists.push({
                  type: 'user',
                  msg: item.feedback_message
                });
              }
            }
            if (this.feedbackTip) { //每次打开游戏精灵时提示一次
              let servicerTip =`主人在游戏中遇到任何问题，都可以通过以下方式获取帮助哦，人鱼宝宝24小时守护着您。`;
              lists.unshift({
                type: 'servicer',
                msg: servicerTip,
                contactBox: true
              });

              this.feedbackTipMut();
            }
            this.feedBackHistory(lists.reverse());
          }
          // _this.pushFeedBackList({type: 'servicer', msg: res.data.message});
        });
      },
      feedbackToTop() {
        if (this.scroll) {
          this.scroll.scrollTo(0, this.scroll.minScrollY, 600);
        }
      },
      feedbackToBottom() {
        if (this.scroll) {
          let _this = this;
          setTimeout(() => {
            _this.scroll.refresh();
            _this.scroll.scrollTo(0, _this.scroll.maxScrollY, 600);
          }, 60);
        }
      },
      handleModal(type) {
        this.modal = type;
      },
      copy(type, msg) {
        let os = judgeClientOs();

        this.handleModal(type);

        if(os == 'iOS') {
            window.webkit.messageHandlers.iOS.postMessage({functionName:'copy', parameter: msg});
        }
        if(os == 'Android') {
            yhGameSpirit.copyText(msg);
        }
      },
    },
    computed: {
      ...mapState([
        'dialogToTop',
        'feedBackList',
        'urlParams',
        'feedbackTip',
        'baseInfo',
        'feedBackMsg'
      ]),
      handleWxConf() {
        if(this.baseInfo &&  this.baseInfo.channel_configure) {

        }
      },
      handleQqconf() {

      }
    },
    created() {
      // alert('触发feedback created');
      if (this.feedbackTip) {
        this.queryFeedbackHistory();
      }

      if(this.feedBackMsg == 'show') {
        this.handleFeedBackMsgStatus('hide');//新消息小红点
        feedbackMsgStatusReported(this.urlParams.role_id);
      }
    },
    mounted() {

      let _this = this;

      this.$nextTick(() => {
        _this.scroll = new Bscroll('.feedback-wrapper', {
          click: true,
          tap: false,
          preventDefaultException: {
            className: /(^|\s)click(\s|$)/
          }
        });

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
  .feedback-m {
    height: 100%;
    padding: 20px 10px 0;
    overflow: hidden;

    .light-top {
      //对话框顶部光效
      position: absolute;
      top: -11px;
      width: 78px;
      height: 20px;
      margin-left: -39px;
      background: url("../../assets/common/server-light-top.png") no-repeat;
      background-size: 100% 100%;
    }

    .light-bottom {
      //对话框底部光效
      position: absolute;
      bottom: -11px;
      width: 130px;
      height: 20px;
      // margin-left: -65px;
      background: url("../../assets/common/server-light-bottom.png") no-repeat;
      background-size: 100% 100%;
    }
    .text {
      min-width: 130px;
    }
    .servicerItem {
      margin-bottom: 26px;
    }

    .servicer-icon {
      width: 74px;
      height: 77px;
      background: url('../../assets/feedback/servicer-icon.png') no-repeat;
      background-size: 74px 77px;
    }

    .servicer-msg-text {
      position: relative;
      max-width: 482px;
      min-height: 58px;
      text-align: initial;
      color: #ffffff;
      font-size: 20px;
      background: #3f3f55;
      border: 2px solid #b0a990;
      border-radius: 10px;
      padding: 10px 14px 6px;
      margin-left: 22px;
      flex-basis: 1;
      align-self: center;
      display: inline-block;

      .flag {
        position: absolute;
        left: -15px;
        top: 22px;
        width: 19px;
        height: 25px;
        background: url('../../assets/feedback/server-arrow.png') no-repeat;
        background-size: 100% 100%;
      }
      .light-top {
        left: 50%;
        margin-left: -65px;
      }

      .light-bottom {
        right: 10%;
      }
    }

    .wx-qq-box {
      margin: 14px 0 0 96px;
      width: 482px;

      >div {
        width: 232px;
        height: 75px;
        border: 1px solid #9bb4d7;
        /*no*/
        border-radius: 10px;
        font-size: 14px;
        color: #ffffff;
        text-align: initial;
        flex-shrink: 0;
        box-shadow: 0 0 16px #2a4787;
      }

      .wx,
      .qq {
        padding: 0 10px;
      }

      .text {
        font-size: 18px;
        flex-shrink: 0;
        width: 130px;
        text-align: center;
      }

      .copy-btn {
        font-size: 20px;
        display: block;
        flex-shrink: 0;
        width: 81px;
        height: 39px;
        line-height: 38px;
        text-decoration: none;
        color: #ffffff;
        text-align: center;
        background: url('../../assets/feedback/copy-btn.png') no-repeat;
        background-size: 100% 100%;
      }
    }

    .userItem {
      display: flex;
      justify-content: flex-end;
      margin-bottom: 26px;

      .user-msg-box {
        max-width: 482px;
        margin: 0 22px 0 0;
        display: inline-block;
        background: #3f3f55;
        color: #ffffff;
        font-size: 20px;
        text-align: initial;
        background: #3f3f55;
        border: 2px solid #b0a990;
        border-radius: 10px;
        padding: 10px 14px 6px;
        flex-basis: 1;
        align-self: center;
        position: relative;
        min-height: 58px;
      }

      .flag {
        position: absolute;
        top: 18px;
        right: -17px;
        width: 19px;
        height: 25px;
        background: url('../../assets/feedback/user-arrow.png') no-repeat;
        background-size: 100% 100%;
      }

      .userc-icon {
        width: 75px;
        height: 84px;
        background: url('../../assets/feedback/user-icon.png') no-repeat;
        background-size: 100% 100%;
      }
      .light-top {
        left: 40%;
      }

      .light-bottom {
        left: 10%;
      }
    }
    .copyModal{

      .modal-content{
        top: 52%;
      }
      .close{
        display: block;
        width: 52px;
        height: 54px;
        text-decoration: none;
        background: url('../../assets/common/close-btn.png') no-repeat;
        background-size: 100% 100%;
        position: absolute;
        top: -10px;
        right: 30px;
      }
      .close-copy-btn{
        display: block;
        text-decoration: none;
        position: absolute;
        top: 0;
        right: 0;
        width: 164px;
        height: 63px;
        line-height: 63px;
        background: url('../../assets/common/copy-close.png') no-repeat;
        background-size: 100% 100%;
        color: #ffffff;
        font-size: 28px;
        text-align: center;
      }
      .copy-wx-modal,.copy-qq-modal{

        height: 455px;
        background: url('../../assets/common/wx-qq-modal.png') no-repeat;
        background-size: 1292px 455px;
      }
      .copy-wx-modal{
        width: 670px;
        background-position: -621px 0;
        .wx-qrcode{
          position: absolute;
          top: 150px;
          right: 140px;
          width: 160px;
          height: 160px;
          background: url('../../assets/common/wx-QRcode.png') no-repeat;
          background-size: 140px 140px;
          background-position: center;
          background-color: #1d222c;
          border: 1px solid #3d4862;/*no*/
        }
        .close-copy-btn{
          top: 320px;
          right: 140px;
        }
      }
      .copy-qq-modal{
        width: 621px;
        background-position: 0 0;
        .close-copy-btn{
          top: 260px;
          right: 146px;
        }
        .close{
          right: 60px;
        }
      }
    }
  }

</style>

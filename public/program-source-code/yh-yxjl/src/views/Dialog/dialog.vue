<template>
  <div class="dialog-m dialog-wrapper">
    <div class="content" id="dialog-cont" @click="triggerKeyword">
      <div v-for="(item, index) in dialogListAddQuestionState" :key="index">
        <div v-if="item.type == 'user'">
          <div class="userItem click">
            <div class="user-msg-box">
              <div class="flag"></div>
              <div class="light-top"></div>
              <div class="text">{{ item.msg }}</div>
              <div class="light-bottom"></div>
            </div>
            <div class="userc-icon"></div>
          </div>
        </div>
        <div v-if="item.type == 'servicer'">
          <div class="servicerItem click">
            <div class="servicer-msg-box fle">
              <div class="servicer-icon"></div>
              <div class="servicer-msg-text click">
                <div class="flag"></div>
                <div class="light-top"></div>
                <div class="text" v-html="item.msg"></div>
                <div class="light-bottom"></div>
                <div class="servicer-dialog flex-sb-c" v-if="!item.resolveState">
                  <span>人鱼宝宝解决您的问题了吗？</span>
                  <div>
                    <a
                      href="javascript:;"
                      class="dialog-resolve-btn click"
                      @click="answerFeedback(index, 'resolve')"
                    >解决</a>
                    <a
                      href="javascript:;"
                      class="dialog-unsolved-btn click"
                      @click="answerFeedback(index, 'unsolved')"
                    >未解决</a>
                  </div>
                </div>
                <div
                  class="servicer-dialog flex-c-c"
                  style="text-align: center;"
                  v-if="item.resolveState"
                >
                  <span>感谢您的反馈</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
    <a href="javascript:;" v-if="scrollToTopBtnStatus == 'show'" class="scroll-top click" @click="scrollToTop"></a>
  </div>
</template>
<script>
import { mapState, mapActions, mapMutations } from "vuex";
import Bscroll from "better-scroll";
import { keywordReq } from "../../api/index.js";
import { matchKeyWord } from "../../utils/utils.js";

export default {
  name: "dialogPanel",
  data() {
    return {
      resolveState: false,
      scrollToTopBtnStatus: 'hide'
    };
  },
  methods: {
    ...mapActions(["syncPushDialogList"]),
    ...mapMutations(["handleAnswerFeedback"]),
    scrollToBottom() {
      let _this = this;

      if (this.scroll) {
        let timeout = setTimeout(() => {
          //dom渲染结束后滚动到底部
          _this.scroll.refresh(); //新增数据，重新计算better-scroll
          this.handleResponseMgs_y();
          // _this.scroll.scrollTo(0, _this.scroll.maxScrollY, 600);

          if(this.scroll.hasVerticalScroll && this.scrollToTopBtnStatus == 'hide') {
            this.scrollToTopBtnStatus = 'show'
          }
        }, 160);
      }
    },
    scrollToTop() {
      if(this.scroll) {
        this.scroll.scrollTo(0, 0, 600);
      }
    },
    answerFeedback(index, type) {
      let arr = this.dialogList[index];

      this.handleAnswerFeedback({
        index: index,
        text: {
          msg: arr.msg,
          resolveState: type,
          type: arr.type}
      });

    },
    dialogListToTop() {
      if (this.scroll) {
        this.scroll.scrollTo(0, this.scroll.minScrollY, 600);
      }
    },
    triggerKeyword(e) {
      if (e.target.className == "bn-tag-keyword") {

        keywordReq(e.target.innerText, 2, this.urlParams).then(res => {

          let data;
          if(res.data.data) {
            data = matchKeyWord(res.data.data.content)
          } else {
            data = '敬请期待！';
          }
          this.syncPushDialogList({ type: "servicer", msg: data });
          let settim = setTimeout(() => {
            this.scrollToBottom();
          }, 120);
        });
      } else {
        return false;
      }
    },
    handleResponseMgs_y() {
      if(!this.scroll) { return false;}

      let content  = document.getElementById('dialog-cont');
      let lastMsg = content.lastChild;

      if(!lastMsg) { return false; }

      if(lastMsg.clientHeight <= this.scroll.wrapperHeight) {
        this.scroll.scrollTo(0, this.scroll.maxScrollY, 600);
      } else {

        let scrollY = lastMsg.clientHeight - this.scroll.wrapperHeight;

        this.scroll.scrollTo(0, this.scroll.maxScrollY + scrollY, 800);
      }
    }
  },
  computed: {
    ...mapState(["dialogList", "dialogToTop", "urlParams"]),
    dialogListAddQuestionState() {
      //原始返回数据新增 resolveState属性
      for (let item of this.dialogList) {
        if (item.hasOwnProperty("resolveState")) {
          return this.dialogList;
        }
        item.resolveState = "";
      }

      return this.dialogList;
    }
  },
  mounted() {
    let _this = this;

    this.$nextTick(() => {
      _this.scroll = new Bscroll(".dialog-wrapper", {
        click: true,
        tap: false,
        preventDefaultException: {
          className: /(^|\s)click(\s|$)/
        }
      });
      if (this.$route.query.type == "history") {
        return false;
      }

      let timeout = setTimeout(() => {
        //dom渲染结束后滚动到底部
        this.scrollToBottom();
      }, 500);
    });
  },
  watch: {
    'dialogList.length': 'scrollToBottom'
  }
};
</script>
<style lang="scss">
.dialog-m {
  height: 99%;
  padding: 0 10px 0;
  overflow: hidden;
  position: relative;

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
    background: url("../../assets/feedback/servicer-icon.png") no-repeat;
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
      background: url("../../assets/feedback/server-arrow.png") no-repeat;
      background-size: 100% 100%;
    }
    .text {
      img{
        max-width: 100%;
      }
    }
    .light-top {
      left: 50%;
      margin-left: -65px;
    }

    .light-bottom {
      right: 10%;
    }
    .servicer-dialog {
      width: 100%;
      height: 50px;
      margin-top: 15px;
      border-top: 1px dashed #46465d; /*no*/
      span {
        color: #889cbc;
        font-size: 20px;
      }
    }
    .dialog-resolve-btn,
    .dialog-unsolved-btn {
      display: inline-block;
      width: 84px;
      height: 33px;
      line-height: 40px;
      background: url("../../assets/dialog/resolve-btn.png") no-repeat;
      background-size: 100% 100%;
      color: #ffffff;
      text-align: center;
      text-decoration: none;
      font-size: 20px;
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
      background: url("../../assets/feedback/user-arrow.png") no-repeat;
      background-size: 100% 100%;
    }

    .userc-icon {
      width: 75px;
      height: 84px;
      background: url("../../assets/feedback/user-icon.png") no-repeat;
      background-size: 100% 100%;
    }

    .light-top {
      left: 40%;
    }

    .light-bottom {
      left: 10%;
    }
  }
  .scroll-top{
    display: block;
    width: 81px;
    height: 47px;
    background: url('../../assets/dialog/scroll-top.png');
    background-size: 81px 47px;
    position: absolute;
    top: 400px;
    right: 5px;
  }
}
</style>

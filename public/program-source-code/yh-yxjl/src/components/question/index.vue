<template>
  <div class="question">
    <input v-model="inputVal" type="text"
      placeholder="请输入要提问的内容" name="question" id="quest">
    <a href="javascript:;" @click="pushDialog" class="question-btn">
      <span>{{ dialogType == 'dialog' ? '提 问' : '提建议' }}</span>
    </a>
  </div>
</template>
<script>
  import {
    keywordReq,
    feedbackSubReq
  } from '../../api/index.js'
  import {
    mapState,
    mapActions,
    mapMutations
  } from 'vuex'
  import {
    matchKeyWord
  } from '../../utils/utils.js'

  export default {
    name: 'question',
    data() {
      return {
        inputVal: '',
        dialogType: 'dialog'
      }
    },
    computed: {
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
        let inputVal = this.inputVal.trim();


        if (this.$route.name == 'feedback') {
          // this.$router.push();
          if (inputVal) {
            this.pushFeedBackList({
              type: 'user',
              msg: inputVal
            });
            feedbackSubReq(inputVal, this.urlParams).then((res) => {
              this.inputVal = '';

              this.pushFeedBackList({
                type: 'servicer',
                msg: res.data.message
              });
            });
          }
          return false;
        }
        if (this.$route.name != 'dialog') {
          this.$router.push({
            path: 'dialog'
          })
        }

        if (inputVal) {
          // this.$store.commit('pushDialogList', {type: 'user', msg: this.inputVal});
          this.pushDialogList({
            type: 'user',
            msg: inputVal
          });
          keywordReq(this.inputVal, 2, this.urlParams).then((res) => {
            let data = res.data.data;
            this.inputVal = '';

            if (data) {
              this.pushDialogList({
                type: 'servicer',
                msg: matchKeyWord(data.content)
              });
            } else {
              this.pushDialogList({
                type: 'servicer',
                msg: '人鱼宝宝不太清楚您的意思。。。'
              })
            }

          });
        } else {
          return false;
        }

      },
      inputExchange() {
        if (this.$route.name == 'feedback') {
          this.dialogType = 'feedback';
        } else {
          this.dialogType = 'dialog';
        }
      },
      historyRecord() {

        if(!this.inputVal) {
          return false;
        }

        this.dialogListToTop();

        if (this.$route.name == 'feedback') {
          this.$router.push({
            path: 'feedback',
            query: {
              type: 'history'
            }
          });
        } else {
          this.$router.push({
            path: 'dialog',
            query: {
              type: 'history'
            }
          });
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
  .question {
    width: 646px;
    height: 66px;
    position: relative;
    background: url('../../assets/common/search-bg.png') no-repeat;
    background-size: 100% 100%;
    margin-top: 30px;
    text-align: initial;

    input::-webkit-input-placeholder {
      text-align: center;
      color: #677893;
    }

    .question-btn {
      display: block;
      width: 128px;
      height: 59px;
      line-height: 59px;
      background: url('../../assets/common/questioning.png') no-repeat;
      background-size: 100% 100%;
      position: absolute;
      top: 3px;
      right: -48px;
      text-align: center;
      color: #ffffff;
      text-decoration: none;

    }
  }

  #quest {
    background: rgba(255, 255, 255, 0);
    width: 570px;
    height: 66px;
    padding: 0 20px;
    color: #ffffff;
    font-size: 30px;
  }

</style>

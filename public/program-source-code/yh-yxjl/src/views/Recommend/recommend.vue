<template>
  <div class="recommend-m recommend-wrapper">
    <div class="content flex-wrap">

      <a class="item click" href="javascript:;"
        v-for="(item, index) in recommendLists" :key="index"
        @click="triggerDialog(item.rule, 1)">
        <div class="item-flag click">{{ item.label == 1 ? "NEW" : "HOT" }}</div>
        <img :src="imgDomain + '/' + item.img" class="click" alt="icon">
        <div class="cover-bar click">{{ item.title }}</div>
      </a>
      <!-- <a class="item click" href="javascript:;">
        <div class="item-flag click">NEW</div>
        <img src="../../assets/recommend/item-icon.png" class="click" alt="icon">
        <div class="cover-bar click">精灵进阶</div>
      </a> -->
    </div>
  </div>
</template>
<script>
  import BScroll from 'better-scroll'
  import {
    mapState
  } from 'vuex';
  import {
    judgeClientOs,
    matchKeyWord
  } from '../../utils/utils.js'
  import {
    ruleComponentReq,
    keywordReq,
    baseInfoReq
  } from '../../api/index.js'

  export default {
    name: 'recommend',
    data() {
      return {
        recommendLists: null,
        baseInfo: null,
        modal: ''
      }
    },
    computed: {
      ...mapState([
        'baseConf',
        'imgDomain',
        'dialogList',
        'urlParams'
      ]),
    },
    methods: {
      requestHotspotcont() { //请求板块内容
        let _this = this;

        ruleComponentReq(this.baseConf[1].id)
          .then(function (res) {

            _this.recommendLists = res.data.data;
          });
      },
      triggerDialog(keyword, type) { //触发关键字
        let _this = this;

        _this.$router.push({
          path: 'dialog'
        });
        _this.$store.commit('pushDialogList', {
          type: 'user',
          msg: keyword
        })

        keywordReq(keyword, type, this.urlParams).then((res) => {

          let data = res.data.data;
          let msg = '';

          if (data) {
            msg = matchKeyWord(data.content);
          } else {
            msg = '敬请期待';
          }
          _this.$store.dispatch('syncPushDialogList', {
            type: 'servicer',
            msg: msg
          });
        })
      }
    },
    created() {
      let _this = this;
      if (this.baseConf) {
        this.requestHotspotcont();
      }
      // baseInfoReq(this.urlParams.game_id, this.urlParams.channel_id).then(function (res) {

      //   _this.baseInfo = res.data.data;
      // });
    },
    mounted() {
      let scroll = new BScroll('.recommend-wrapper', {
        click: true,
        tap: false,
        preventDefaultException: {
          className: /(^|\s)click(\s|$)/
        }
      });
    },
    watch: {
      'baseConf': 'requestHotspotcont'
    }
  }

</script>
<style lang="scss">
  .recommend-m {
    height: 100%;
    overflow: hidden;
    padding: 0 12px 0 14px;
    .content{
      padding-top: 20px;
    }
    .item {
      display: block;
      width: 213px;
      height: 132px;
      background: url('../../assets/recommend/bg.png') no-repeat;
      background-size: 100% 100%;
      position: relative;
      padding: 1px;
      overflow: hidden;
      text-align: center;
      margin: 0 0 20px 0;
      >img {
        width: 211px;
        height: 129px;
      }
    }
    .item:nth-child(3n-1) {
      margin: 0 14px;
    }
    .item-flag {
      position: absolute;
      top: 7px;
      left: -2px;
      width: 66px;
      height: 30px;
      line-height: 36px;
      text-align: center;
      color: #ffffff;
      font-size: 16px;
      background: url('../../assets/recommend/hot-icon.png') no-repeat;
      background-size: 100% 100%;

    }

    .cover-bar {
      position: absolute;
      bottom: 2px;
      width: 212px;
      height: 50px;
      line-height: 50px;
      font-size: 20px;
      color: #ffffff;
      background: rgba(0, 0, 0, .6);
    }
  }

</style>

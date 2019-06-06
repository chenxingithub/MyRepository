<template>
  <div class="hotspot-main hotspot-wrapper">
    <div class="content">
      <div class="block-box fle">
        <div class="slide click">
          <swiper :options="hotspotSwiperOption" ref="mySwiper">
            <swiper-slide v-for="(item, index) in handleSlide" :key="index" class="click">
              <img :src="imgDomain + '/' + item.img" alt="slide" @click="triggerDialog(item.rule, 1)">
            </swiper-slide>
            <div class="hotspot-swiper-pagination"  slot="pagination"></div>
          </swiper>
          <div class="slide-tit">{{ slideTit }}</div>
        </div>
        <div class="list flex-wrap">
          <div class="click" @click="triggerDialog('初识精灵', 1)"></div>
          <div class="click" @click="triggerDialog('全面养成', 1)"></div>
          <div class="click" @click="triggerDialog('打宝须知', 1)"></div>
          <div class="click" @click="triggerDialog('副本介绍', 1)"></div>
          <div class="click" @click="triggerDialog('装备打造', 1)"></div>
          <div class="click" @click="triggerDialog('修复内容', 1)"></div>
        </div>
      </div>
      <div class="item-box flex-wrap-sb-c">
        <a class="item click" href="javascript:;" v-for="(item, index) in handleHotspotList" :key="index"
          @click="triggerDialog(item.rule, 1)">
          <img src="../../assets/hotspot/spot-icon.png" alt="spot">{{item.title}}
        </a>
      </div>
    </div>
  </div>
</template>
<script>
  import BScroll from 'better-scroll';
  import { mapState, mapMutations, mapActions } from 'vuex';
  import { ruleComponentReq, keywordReq } from '../../api/index.js'
  import { matchKeyWord } from '../../utils/utils.js'
  import 'swiper/dist/css/swiper.css'
  import { swiper, swiperSlide } from 'vue-awesome-swiper'

  export default {
    name: 'hotspot',
    components: {
      swiper,
      swiperSlide
    },
    data() {
      return {
        hotspotList: null,
        slideTit: null,
        hotspotSwiperOption: {
          autoplay: true,
          // loop : true,
          pagination: {
            el: '.hotspot-swiper-pagination',
          },
          on: {
            slideChangeTransitionStart: () => {
              this.slideTit = this.handleSlide[this.swiper.activeIndex].rule;
            },
        },
        }
      }
    },
    computed: {
      ...mapState([
        'baseConf',
        'imgDomain',
        'dialogList',
        'urlParams'
      ]),
      swiper() {
        return this.$refs.mySwiper.swiper
      },
      handleHotspotList() {
        if(Array.isArray(this.hotspotList)) {
          return this.hotspotList.slice(2);
        } else {
          return this.hotspotList;
        }
      },
      handleSlide() {
        if(Array.isArray(this.hotspotList)) {
          this.slideTit = this.hotspotList[0].rule;//轮播标题初始值设置
          return this.hotspotList.slice(0,2);
        } else {
          return this.hotspotList;
        }
      }
    },
    methods: {
      requestHotspotcont() { //请求板块内容
        let _this = this;

        ruleComponentReq(this.baseConf[0].id)
          .then(function (res) {

            _this.hotspotList = res.data.data;
          })
      },
      triggerDialog(keyword, type) { //请求对话后保存
        let _this = this;

        _this.$router.push({
          path: 'dialog'
        });
        _this.$store.commit('pushDialogList', {
          type: 'user',
          msg: keyword
        })

        // return false;//调试->阻止请求

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
      if (this.baseConf) {
        this.requestHotspotcont();
      }
    },
    mounted() {
      this.$nextTick( () => {
        let wrapper = document.querySelector('.hotspot-wrapper');
        let scroll = new BScroll(wrapper, {
          click: true
        });
      })
    },
    watch: {
      'baseConf': 'requestHotspotcont'
    }
  }

</script>
<style lang="scss">
  .hotspot-main {
    overflow: hidden;
    height: 100%;
    padding: 0 11px;
    .content{
      padding-top: 14px;
    }
    .slide {
      width: 283px;
      height: 220px;
      margin: 0 3px 0 0;
      background: url('../../assets/hotspot/slide-bg.png') no-repeat;
      background-size: 100% 100%;
      // background-position: 0 5px;
      flex-shrink: 0;
      padding-top: 2px;

      .swiper-container{
        width: 280px;
        height: 175px;
      }
      .swiper-slide{
        height: 180px;
        // background: #7cf7f0;
      }
      img{
        width: 100%;
      }
      .hotspot-swiper-pagination{
        position: absolute;
        bottom: 0;
        left: 0;
        z-index: 10;
      }
      .swiper-pagination-bullet{
        width: 8px;/*no*/
        height: 8px;/*no*/
        border-radius: 8px;/*no*/
        background: #93a7c7;
      }
      .swiper-pagination-bullet-active{
        background: #445473;
      }
      .slide-tit{
        width: 283px;
        height: 43px;
        line-height: 43px;
        font-size: 22px;
        // margin: 0 1px;
        background: url('../../assets/hotspot/slide-tit-bg.png') no-repeat;
        background-size: 100% 100%;
        background-position: center;
        color: #dbe7ff;
        text-align: center;
      }
    }

    .list {

      // padding: 0 6px 0 0;
      >div {
        width: 123px;
        height: 106px;
        margin: 0 0 7px 7px;
        flex-shrink: 0;
        background-size: 100% 100%;
        background-repeat: no-repeat;
      }

      >div:nth-child(1) {
        background-image: url('../../assets/hotspot/item-1.png');
      }

      >div:nth-child(2) {
        background-image: url('../../assets/hotspot/item-2.png');
      }

      >div:nth-child(3) {
        background-image: url('../../assets/hotspot/item-3.png');
      }

      >div:nth-child(4) {
        background-image: url('../../assets/hotspot/item-4.png');
      }

      >div:nth-child(5) {
        background-image: url('../../assets/hotspot/item-5.png');
      }

      >div:nth-child(6) {
        background-image: url('../../assets/hotspot/item-6.png');
      }
    }

    .item-box {
      padding: 0 7px;
      margin-top: 16px;

      .item {
        display: block;
        font-size: 22px;
        width: 312px;
        height: 55px;
        line-height: 55px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        text-align: initial;
        color: #9bb4d7;
        text-decoration: none;
        border-bottom: 1px dashed #363d4b;/*no*/
      }

      span {
        display: inline-block;
        width: 10px;
        height: 10px;
        background: #7cf7f0;
      }

      img {
        margin-right: 14px;
      }
    }
  }

</style>

<template>
  <div id="homePage" class="wrap swiper-container" ref="homePage">
    <div class="swiper-wrapper">
      <div class="swiper-slide page_1 page_box">
        <home-head
          @slideNext="handleSlideNext"
          @toggleVideo="hadleVideoToggle"></home-head>
      </div>
      <div class="swiper-slide page_2 page_box">
        <news-info
          @slideNext="handleSlideNext"
          :article_ids="articleIds"
          :article_lists="articleLists"
          :focus_lists="focusLists"></news-info>
      </div>
      <div class="swiper-slide page_3 page_box">
        <mercenaries
          @slideNext="handleSlideNext"
          :mercenaries_lists="articleLists"
          :mercenaries_ids="articleIds"></mercenaries>
      </div>
      <div class="swiper-slide page_4 page_box">
        <gameFeatures :gameFeatures_focus="focusLists"></gameFeatures>
        <qrcode></qrcode>
        <foot></foot>
      </div>
      <!-- <div class="swiper-slide page_5 page_box">
        <qrcode></qrcode>
        <foot></foot>
      </div> -->
    </div>
    <div class="nav"><!-- 导航栏 -->
      <div class="nav-box">
        <router-link :to="{path: '/giftBag'}" tag="div">
          <img src="../../assets/images/nav-gift-code-btn-bg.png" alt="image">
        </router-link>
        <div @click="handleSlideTo(0)" :class="{'slide-active': activeSlide == 0}">
          <img src="../../assets/images/nav-head-bg.png" alt="image">
        </div>
        <div @click="handleSlideTo(1)" :class="{'slide-active': activeSlide == 1}">
          <img src="../../assets/images/nav-news-info-bg.png" alt="image">
        </div>
        <div @click="handleSlideTo(2)" :class="{'slide-active': activeSlide == 2}">
          <img src="../../assets/images/nav-mercenaries-bg.png" alt="image">
        </div>
        <div @click="handleSlideTo(3)" :class="{'slide-active': activeSlide == 3}">
          <img src="../../assets/images/nav-gameFeatures-bg.png" alt="image">
        </div>
      </div>
    </div>
    <div class="head-video modal-cover" v-if="videoStatus == 'show'"><!-- 视屏弹窗 -->
      <div class="modal-content">
        <div class="video-close" @click="hadleVideoToggle"></div>
        <!-- <iframe id="videoFrame" :src="baseInfo.video_url" frameborder="0"></iframe> -->
        <video id="videoFrame" controls="" autoplay="" name="media">
          <source :src="baseInfo.video_url" type="video/mp4">
        </video>
      </div>
    </div>
  </div>
</template>
<script>
import homeHead from './homeHead'
import newsInfo from './newsInfo'
import mercenaries from './mercenaries'
import gameFeatures from './gameFeatures'
import qrcode from './QRcode'
import foot from '../../components/foot/index'
import Swiper from '../../plugins/swiper/idangerous.swiper2.7.6.js'
import '../../plugins/swiper/idangerous.swiper2.7.6.css'
import { isPC } from '../../utils/utils.js'
import { mapState } from 'vuex'
import {
  request_articleChildrenList,
  request_articleList,
  request_focus_picture } from '../../api/index.js'

export default {
  name: 'HomePage',
  components: {
    homeHead,
    newsInfo,
    mercenaries,
    gameFeatures,
    qrcode,
    foot
  },
  data() {
    return {
      swiper: null,
      articleIds: [73,51,49,50,72], //综合，公告，攻略，活动，佣兵介绍
      focusIds: [1,7],//焦点图数据源[手机端，PC端]
      articleLists: null,
      focusLists: null,
      videoStatus: 'hide',
      activeSlide: 0
    }
  },
  computed: {
    ...mapState([
      'baseInfo'
    ]),
  },
  methods: {
    wrap() {//整屏滚动适配
      let _this = this;
      var i, s, n = $(window),
        o = $("#homePage"),
        t = this.swiper,
        a = $(".menu a"),
        r = $(".swiper-slide.page_box");

      if (i = n.height(), s = n.width(), n.height() > 850 ? (i = n.height(), o.css("height", i)) : (i = 1e3, o.css("height", n.height())), s = n.width() > 1200 ? n.width() : 1200, n.width() > 1500 ? a.show() : a.hide(), r.css("height", i), r.eq(3).css("height", 1673), null == t)
            this.swiper = new Swiper("#homePage", {
            mode: "vertical",
            speed: 800,
            mousewheelControl: true,
            simulateTouch: !1,
            resizeReInit: !0,
            observer: !0,
            observeParents: !0,
            slidesPerView: "auto",
            initialSlide: 0,
            onSlideChangeStart: function(e) {//slide 滚动时触发
                if(_this.activeSlide != e.activeIndex) {
                  _this.activeSlide = e.activeIndex;
                } else {
                  return false;
                }
            }
        });
        else {
            this.swiper.reInit();
            var e = t.activeIndex;
            t.swipeTo(e)
        }
    },
    handleSlideNext() {

      if(!this.swiper) { return false; }

      this.swiper.swipeNext();
    },
    hadleVideoToggle() {//视频播放，关闭
      if(!this.baseInfo.video_url) {//视屏连接为空
        alert('敬请期待！');
        return false;
      }
      this.videoStatus = this.videoStatus == 'hide' ? 'show' : 'hide';
    },
    handleSlideTo(page) {
      // console.log(page);
      if(!this.swiper) { return false; }
      this.activeSlide = page;
      this.swiper.swipeTo(page, 1000, false);
    }
  },
  created() {
    let page = 1, limit = 6;

    request_articleList(this.articleIds.join(','), page, limit).then(res => {//新闻资讯文章列表
      // console.log(res);
      this.articleLists = res.data;//返回 综合，公告，攻略，活动，佣兵介绍文章列表
    });
    request_focus_picture(this.focusIds.join(',')).then(res => {//轮播图为手机端位置二
      // console.log(res);
      this.focusLists = res.data.data;
    })
  },
  mounted() {
    this.wrap();
    $(window).resize(() => { this.wrap() });

  }
}
</script>
<style lang="scss">
#homePage{
  width: 100%;
  // min-height: 1000px;
  min-width: 1200px;
  font-family: "\5FAE\8F6F\96C5\9ED1","Microsoft YaHei";
  position: relative;
  overflow: hidden;
  .swiper-slide {
    position: relative;
    // height: 1000px;
    width: 100%;
    overflow: hidden;
    background: transparent;
  }
  .page_1{
    width: 100%;
    // height: 1000px;
    // background: #efbfbf;
  }
  .page_2{
    width: 100%;
    // height: 1000px;
    // background: #6ef2f0;
  }
  .page_3{
    width: 100%;
    // height: 1000px;
    // background: #f2c6f8;
  }
  .page_4{
    width: 100%;
    // height: 1000px;
    // background: #94adf2;
  }
  .nav{
    position: fixed;
    top: 50%;
    right: -25px;
    width: 302px;
    height: 653px;
    margin: -301px 0 0 0;
    @include bg('../../assets/images/nav-bg.png');
    $h: 62px;
    .nav-box{
      // background: rgba(86, 236, 161, .3);
      width: 140px;
      position: absolute;
      right: 32px;
      top: -7px;
      >div{
        width: 140px;
        height: $h;
        cursor: pointer;
        >img{
          vertical-align: middle;
          margin: 20px 10px;
        }
      }
      >div:hover{
        @include bg('../../assets/images/nav-selected-bg.png');
      }
      div:nth-child(1){
        width: 100%;
        height: $h;
        >img{
          margin: 0 0 0 -5px;
        }
      }
      div:nth-child(1):hover{
        background: none;
      }
      .slide-active{
        @include bg('../../assets/images/nav-selected-bg.png');
      }
    }
  }
  .head-video{//video 视频弹窗
    background: rgba(1,2,3, .8);
    .modal-content{
      width: 800px;
      height: 450px;
      background: #000000;
    }
    .video-close{
      width: 58px;
      height: 58px;
      position: absolute;
      top: 0;
      right: -70px;
      cursor: pointer;
      @include bg('../../assets/images/video-close.png');
    }
    #videoFrame{
      width: 100%;
      height: 100%;
    }
  }
}
</style>

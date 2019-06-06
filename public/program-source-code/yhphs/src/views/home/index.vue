<template>
  <div id="home-page">
    <!-- <head-nav></head-nav> -->
    <!-- <div id="loadingPage" style="display: none;">
      <div class="load-box">
        <div class="load-in"></div>
      </div>
      <div class="cunt-box flex-c">
        <div class="load-num">0</div>
        <div class="load-flag">
          <p>%</p>
          <p>LOADING <span class="dot-ani1">.</span><span class="dot-ani2">.</span><span class="dot-ani3">.</span></p>
        </div>
      </div>
    </div> -->
    <swiper :options="swiperOption" ref="mySwiper" class="home-swiper-container">
      <swiper-slide class="home-slide page1">
        <div class="bg">
          <!-- <video  loop="loop" src="../../assets/video/home.mp4" controls id="video" autoplay="autoplay">
            <source src="../../assets/video/home.mp4" >
          </video> -->
          <!-- <video src="../../assets/video/home.mp4" controls="controls" autoplay="autoplay"></video>
          <canvas id="myCanvas"></canvas> -->
        </div>
        <head-main></head-main>
      </swiper-slide>
      <swiper-slide class="home-slide">
        <news-info :toNext="clickToNext"  ref="newsInfo"></news-info>
      </swiper-slide>
      <swiper-slide class="home-slide">
        <occpation-intro :toNext="clickToNext" ref="occpationIntro"></occpation-intro>
      </swiper-slide>
      <swiper-slide class="home-slide">
        <pet-intro :toNext="clickToNext" ref="petIntro"></pet-intro>
      </swiper-slide>
      <swiper-slide class="home-slide">
        <game-features :toNext="clickToNext" ref="gameFeatures"></game-features>
      </swiper-slide>
      <swiper-slide id="head-slid-f home-slide">
        <foot-nav :toTop="clickToTop"></foot-nav>
      </swiper-slide>
    </swiper>
    <video-modal
      :videoUrl="videoModal.url"
      @handle-video="handleVideo('hide')"
      v-if="videoModal.status == 'show'">
    </video-modal>
  </div>
</template>
<script>
import headNav from '../../components/head/index'
import headMain from './headMain'
import newsInfo from './newsInfo'
import occpationIntro from './occupationIntrd'
import petIntro from './petIntro'
import gameFeatures from './gameFeatures'
import footNav from '../../components/foot/index'
import videoModal from '../../components/videoModal'
import {mapState, mapMutations} from 'vuex'

import { swiper, swiperSlide } from 'vue-awesome-swiper'
import 'swiper/dist/css/swiper.css'

export default {
  name: 'homePage',
  components: {
    headNav,
    swiper,
    swiperSlide,
    headMain,
    newsInfo,
    occpationIntro,
    petIntro,
    gameFeatures,
    footNav,
    videoModal
  },
  data() {
    return {
      slideStatus: ['loaded','unload','unload','unload','unload'],
      loadStatus: 'loading',
      swiperOption: {
        init: true,
        direction : 'vertical',
        speed: 850,
        noSwiping : true,
        resistanceRatio : 0,//值越小抵抗越大越难将slide拖离边
        // height : window.innerHeight,
        autoHeight: true, //高度随内容变化
        observer:true,
        slidesPerView : 'auto',
        on: {

          slideChangeTransitionStart: () => {
            let  slideIndex = this.swiper.activeIndex;
            if(slideIndex == 1) {
              if(this.slideStatus[slideIndex] == 'unload') {
                this.slideStatus[slideIndex] = 'loaded';
                this.$refs.newsInfo.initSwiper();
              }
            }
            if(slideIndex == 2) {
              if(this.slideStatus[slideIndex] == 'unload') {
                this.slideStatus[slideIndex] = 'loaded';
                this.$refs.occpationIntro.initSwiper();
              }
            }
            if(slideIndex == 3) {
              if(this.slideStatus[slideIndex] == 'unload') {
                // this.slideStatus[slideIndex] = 'loaded';
                // this.$refs.petIntro.initSwiper();
              }
            }
            if(slideIndex == 4) {
              if(this.slideStatus[slideIndex] == 'unload') {

                this.slideStatus[slideIndex] = 'loaded';
                this.$refs.gameFeatures.initSwiper();
              }
            }
          }
        }

      }
    }
  },
  methods: {
   ...mapMutations(['handleVideoModal', 'baseInfo']),
    clickToTop() {
      this.swiper.slideTo(0, 1000, false);//切换到第一个slide，速度为1秒
    },
    clickToNext() {
      this.swiper.slideNext();
    },
    handleVideo(type) {
      this.handleVideoModal({status: type, url: this.url});
    }
  },
  computed: {
    ...mapState(['videoModal']),
    swiper() {
      return this.$refs.mySwiper.swiper
    },
    // slideStatus() {//初始化首页每屏的加载状态
    //   let slideCunt = 5;//首页屏数
    //   return new Array(5).fill('unload');
    // }
  },
  mounted() {

    setTimeout(() => {
      let slides = document.getElementsByClassName('home-slide');
      if(slides) {
        let height = window.innerHeight;
        for(let i = 0, len = slides.length; i < len; i++) {

          slides[i].style.height = height + 'px';
        }
        this.swiper.update();
      }
    }, 0);
    // this.loadStatus = 'loaded';

  }
}
</script>
<style lang="scss">
// #loadingPage{
//   // display: none;
//   position: fixed;
//   width: 750px;
//   height: 100%;
//   background: url('../../assets/images/common/load-bg.jpg') no-repeat;
//   background-size: 750px 1334px;
//   background-position: center bottom;
//   background-color: #ffffff;
//   z-index: 9999;
//   .load-box{
//     width: 329px;
//     height: 329px;
//     margin: 0 auto;
//     position: absolute;
//     top: 40%;
//     left: 50%;
//     margin: -164px 0 0 -164px;
//     background: url('../../assets/images/common/ouset.png') no-repeat;
//     background-size: 100% 100%;
//     transform-origin:50% 50%;
//     animation: loadRotate 4s linear infinite;
//   }
//   .load-in{
//     width: 134px;
//     height: 134px;
//     margin: 98px auto;
//     background: url('../../assets/images/common/inset.png') no-repeat;
//     background-size: 100% 100%;
//     transform-origin:50% 50%;
//     animation: loadRotate 1.4s linear infinite reverse;
//   }
//   .cunt-box{
//     position: absolute;
//     left: 50%;
//     top: 55%;
//     margin-left: -110px;
//     width: 220px;
//     height: 55px;
//   }
//   .load-num{
//     font-size: 56px;
//     text-align: center;
//   }
//   .load-flag{
//     margin-left: 10px;
//     p{
//       margin-block-start: 0;
//       margin-block-end: 0;
//     }
//     span{
//       font-size: 30px;
//     }
//   }
//   .dot-ani1{
//     animation: loadDot 1.2s linear infinite;
//     // animation-delay: 0.5s;
//   }
//   .dot-ani2{
//     animation: loadDot 1.2s linear infinite;
//     animation-delay: 0.2s;
//   }
//   .dot-ani3{
//     animation: loadDot 1.2s linear infinite;
//     animation-delay: 0.4s;
//   }
//   @keyframes loadRotate {
//     from {
//       transform: rotate(0deg);
//     }
//     to {
//       transform: rotate(360deg);
//     }
//   }
//   @keyframes loadDot {
//     from {
//       opacity: 0;
//     }
//     to {
//       opacity: 1;
//     }
//   }
// }
#home-page{
  width: 750px;
  height: 100%;
  background-color: rgba(1,2,3, .3);
  margin: 0 auto;
  position: relative;
  .home-swiper-container,.swiper-wrapper{
    height: 100%;
  }
  .home-slide{
    height: 100%;
    >div{
      height: 100%;
      background-repeat: no-repeat;
      background-size: cover;
      background-position: center bottom;
    }
  }
  .home-slide:last-child{
    height: 463px;
    >div{
      height: 463px;
    }
  }
  .page1{
    .bg{
      position: absolute;
      top: 0;
      left: 0;
      z-index: -1;
      width: 100%;
      height: 100%;
      // background: rgba(1,2,3,.5);
      video{
        position: absolute;
        bottom: 0;
        left: 0;
        width: 750px;
        height: 1334px;
        visibility: hidden;
        z-index: -1;
        // display: none;
      }

    }
    canvas{
      position: absolute;
      bottom: 0;
      left: 0;
      z-index: -1;
      width: 750px;
      height: 1334px;
    }
    background-repeat: no-repeat;
    // background-size: 750px auto;
    background-position: center bottom;
    background-image: url('https://bingniao-cdn3.oss-cn-shenzhen.aliyuncs.com/cms/yhphs/home.gif');
    // background-image: url('https://bingniao-cdn3.oss-cn-shenzhen.aliyuncs.com/cms/yhphs/home.gif');
    // background: url(https://hd.res.netease.com/m/gw/20180514180250/img/bg1_3c9bcf3.jpg) center top no-repeat;
    background-size: cover;
  }
  .next-arrow{// 全屏下一页切换按钮
    position: absolute;
    bottom: 25px;
    left: 50%;
    margin-left: -30px;
    width: 60px;
    height: 34px;
    z-index: 1;
    background: url('../../assets/images/home/arrow-d.png') no-repeat;
    background-size: 100% 100%;
    background-position: center;
    animation: nxtArwAni 1.5s linear infinite;
    // perspective: 500;
    // -webkit-perspective: 500;
    transform-style: preserve-3d;
  }
  @keyframes nxtArwAni {
    0%, 30%{
      opacity: 0;
      -webkit-transform: translate(0, 18px);
      -ms-transform: translate(0, 18px);
    }
    60% {
      opacity: 1;
      -webkit-transform: translate(0, 0);
      -ms-transform: translate(0, 0);
    }
    100% {
      opacity: 0;
      -webkit-transform: translate(0, 6px);
      -ms-transform: translate(0, 6px);
    }
  }
}
</style>

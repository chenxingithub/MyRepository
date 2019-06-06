<template>
  <div id="home-page">
    <head-nav></head-nav>
    <div class="swiper-container home-swiper">
      <div class="swiper-wrapper">
        <div class="swiper-slide home-slide page1">
          <head-main></head-main>
        </div>
        <div class="swiper-slide home-slide">
          <news-info></news-info>
        </div>
        <div class="swiper-slide home-slide">
          <occpation-intro></occpation-intro>
        </div>
        <div class="swiper-slide home-slide">
          <pet-intro></pet-intro>
        </div>
        <div class="swiper-slide home-slide">
          <game-features></game-features>
        </div>
        <div class="swiper-slide" id="head-slid-f">
          <head-foot></head-foot>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import headNav from '../../components/head/index'
import headMain from './headMain'
import newsInfo from './newsInfo'
import occpationIntro from './occupationIntrd'
import petIntro from './petIntro'
import gameFeatures from './gameFeatures'
import headFoot from './headFoot'

// import Swiper from '../../plugins/swiper/swiper.js'
// import '../../plugins/swiper/swiper.css'

import  Swiper from 'swiper'
import 'swiper/dist/css/swiper.min.css'
import Dom7 from 'dom7';

export default {
  name: 'homePage',
  components: {
    headNav,
    headMain,
    newsInfo,
    occpationIntro,
    petIntro,
    gameFeatures,
    headFoot
  },
  data() {
    return {
      swiperOption: ''
    }
  },
  methods: {
    initSwiper() {
      let slides = document.getElementsByClassName('home-slide');
      for(let i = 0, len = slides.length; i < len; i++) {
        slides[i].style.height = window.innerHeight + 'px';
      }
      if(!this.swiperOption) {
        this.swiperOption = new Swiper('.home-swiper', {
          direction : 'vertical',
          speed:800,
          noSwiping : true,
          resistanceRatio : 0,//值越小抵抗越大越难将slide拖离边
          height : window.innerHeight,
          // autoHeight: true, //高度随内容变化
          slidesPerView : 'auto',
          // longSwipes: true,
          // shortSwipes : true,
        });
      } else {
        this.swiperOption.init();
      }
    }
  },
  mounted() {
    this.$nextTick(() => {
      this.initSwiper();
    });
    this.initSwiper();
  },
  updated() {

  },
}
</script>
<style lang="scss">
#home-page{
  width: 750px;
  // min-height: 1334px;
  background-color: rgba(1,2,3, .3);
  margin: 0 auto;
  .swiper-container,.swiper-wrapper{
    height: 100%;
  }
  .swiper-slide{
    height: 100%;
    >div{
      height: 100%;
      background-repeat: no-repeat;
      background-size: cover;
      background-position: center bottom;
    }
  }
  .swiper-slide:last-child{
    >div{
      height: 463px;
    }
  }
  .page1{
    background-repeat: no-repeat;
    background-size: 750px auto;
    background-position: center bottom;
    background-image: url('../../assets/images/home/head-main-bg.png');
    // background: url(https://hd.res.netease.com/m/gw/20180514180250/img/bg1_3c9bcf3.jpg) center top no-repeat;
    // background-size: 100% 100%;
  }
}
</style>

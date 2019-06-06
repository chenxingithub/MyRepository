<template>
  <div class="gameFeatures">
    <div class="gameFeatures-content">
      <div class="gameFeatures-slide swiper-container">
        <div class="swiper-wrapper">
          <div class="swiper-slide" v-for="(item, index) in handleGameFeaturesFocus" :key="index">
            <a :href="item.jump_url || 'javascript:;'" target="_blank">
              <img :src="imgDomain +'/'+ item.picture" alt="image">
            </a>
          </div>
          <!-- <div class="swiper-slide"><img src="../../assets/images/gameFeatures-slide-2.png" alt=""></div>
          <div class="swiper-slide"><img src="../../assets/images/gameFeatures-slide-3.png" alt=""></div> -->
        </div>
        <div class="slide-pre" @click="toExchange('left')"></div>
        <div class="slide-next" @click="toExchange('right')"></div>
      </div>
      <div class="gameFeatures-exch-box">
        <div class="fea-slide-left" @click="toExchange('left')">
          <!-- <img src="../../assets/images/fea-left-arrow.png" alt="left"> -->
        </div>
        <div class="fea-slide-right" @click="toExchange('right')">
          <!-- <img src="../../assets/images/fea-right-arrow.png" alt="left"> -->
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Swiper from '../../plugins/swiper/idangerous.swiper2.7.6.js'
import '../../plugins/swiper/idangerous.swiper2.7.6.css'

export default {
  name: 'gameFeatures',
  props: ['gameFeatures_focus'],//gameFeatures_focus[7]位置7
  data() {
    return {
      swiper: null,
      imgDomain: this.$store.state.imgDomain
    }
  },
  computed: {
    handleGameFeaturesFocus() {
      if(!this.gameFeatures_focus) { return false; }

      return this.gameFeatures_focus[7];
    }
  },
  methods: {
    toExchange(type) {
      if(type == 'left') {
        this.swiper.swipePrev();
      }
      if(type == 'right') {
        this.swiper.swipeNext();
      }
    }
  },
  mounted() {
    this.$nextTick(() => {
      this.swiper = new Swiper(".gameFeatures-slide", {
        centeredSlides: true,
        slidesPerView: 3,
        watchActiveIndex: true,
        initialSlide: 2,
        onlyExternal : true,
      });
    });
  },
  updated() {
    this.$nextTick(() => {
      this.swiper.reInit();
      this.swiper.swipeTo(2, 1000, false);
    });
  }
}
</script>
<style lang="scss">
.gameFeatures{
  width: 100%;
  height: 1080px;
  // background: rgba(67, 245, 177, 0.45);
  @include bg('../../assets/images/game-feature-bg.jpg', 1920px, 1080px, center, center);
  .gameFeatures-content{
    width: 1200px;
    height: 729px;
    position: absolute;
    top: 215px;
    left: 50%;
    margin: 0 0 0 -600px;
  }
  .gameFeatures-slide{
    width: 1150px;
    height: 800px;
    .swiper-slide{
      // height: 729px;
    }
    .swiper-slide {
      height: 100%;
      opacity: 0.4;
      -webkit-transition: 300ms;
      -moz-transition: 300ms;
      -ms-transition: 300ms;
      -o-transition: 300ms;
      transition: 300ms;
      -webkit-transform: scale(0);
      -moz-transform: scale(0);
      -ms-transform: scale(0);
      -o-transform: scale(0);
      transform: scale(0);
    }
    .swiper-slide-visible {
      opacity: 0.5;
      -webkit-transform: scale(0.95) translate(0, 91px);
      -moz-transform: scale(0.95) translate(0, 91px);
      -ms-transform: scale(0.95) translate(0, 91px);
      -o-transform: scale(0.95) translate(0, 91px);
      transform: scale(0.95) translate(0, 91px);
      img{
        margin-top: 22px;
      }
    }
    .swiper-slide-active {
      top: 0;
      opacity: 1;
      -webkit-transform: scale(1.06);
      -moz-transform: scale(1.06);
      -ms-transform: scale(1.06);
      -o-transform: scale(1.06);
      transform: scale(1.06);
      z-index: 2;
      img{
        margin-top: 39px;
      }
    }
    .slide-pre, .slide-next{
      position: absolute;
      top: 126px;
      width: 370px;
      height: 670px;
    }
    .slide-pre{
      left: 0;
    }
    .slide-next{
      right: 0;
    }
  }
  .gameFeatures-exch-box{
    position: absolute;
    top: 18px;
    left: 35px;
    width: 107px;
    height: 53px;
    text-align: center;
    line-height: 53px;
    z-index: 20;
    >div{
      width: 53px;
      height: 53px;
      background: #970e0f;
      cursor: pointer;
    }
    .fea-slide-left{
      float: left;
      @include bg("../../assets/images/fea-left-arrow.png", 10px, 19px, center, center);
    }
    .fea-slide-right{
      float: right;
      @include bg("../../assets/images/fea-right-arrow.png", 10px, 19px, center, center);
    }
  }
}
</style>

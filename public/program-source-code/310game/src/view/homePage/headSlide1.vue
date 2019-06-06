<template>
  <div class="head-slide">
    <div class="head-slide-box">
      <div class="head-slide-item swiper-container">
        <div height="900px" class="head-slide-img swiper-wrapper" v-if="focus_list">
          <div class="slide-img swiper-slide swiper-no-swiping" v-for="(item, index) in focus_list" :key="index" :style="{'background-image': 'url('+imgDomain+'/'+item.picture+')'}">
          </div>
        </div>
        <div class="slide-btn slide-l" @click="handleSlideBtn('prev')"></div>
        <div class="slide-btn slide-r" @click="handleSlideBtn('next')"></div>
        <div class="head-slide-pagination pagination"></div>
      </div>
    </div>
  </div>
</template>
<script>
  import {
    mapState
  } from 'vuex'

  import Swiper from '../../plugins/swiper/idangerous.swiper2.7.6.js'
  // import '../../plugins/swiper/idangerous.swiper2.7.6.css'

  // import '../../plugins/swiper3/swiper.css'
  // import '../../plugins/swiper3/swiper.js'

  export default {
    name: 'headSlide',
    props: ['focus_list'],
    // components: {
    //     Slider,
    //     SliderItem
    // },
    data() {
      return {
        mySwiper: null
      }
    },
    computed: {
      ...mapState(['imgDomain']),
    },
    methods: {
      createSwiper() {
        if (!this.mySwiper) {
          this.$nextTick(() => {
            this.mySwiper = new Swiper('.swiper-container', {
              autoplay: 4000, //可选选项，自动滑动
              loop: true, //可选选项，开启循环
              // autoplay : 4000,
              noSwiping: true,
              speed: 850,
              pagination: '.head-slide-pagination',
              paginationClickable: true,
              autoplayDisableOnInteraction: false,
              progress: true,
              effect: 'fade',
              fade: {
                crossFade: false,
              }
              // onProgressChange: function(swiper){
              //   for (var i = 0; i < swiper.slides.length; i++){
              //     var slide = swiper.slides[i];
              //     var progress = slide.progress;
              //     var translate = progress*swiper.width;
              //     var opacity = 1 - Math.min(Math.abs(progress),1);
              //     slide.style.opacity = opacity;
              //     swiper.setTransform(slide,'translate3d('+translate+'px,0,0)');
              //   }
              // },
              // onTouchStart:function(swiper){
              //   for (var i = 0; i < swiper.slides.length; i++){
              //     swiper.setTransition(swiper.slides[i], 0);
              //   }
              // },
              // onSetWrapperTransition: function(swiper, speed) {
              //   for (var i = 0; i < swiper.slides.length; i++){
              //     swiper.setTransition(swiper.slides[i], speed);
              //   }
              // }
            });
          })
        } else {
          this.mySwiper.reInit();
        }
      },
      handleSlideBtn(type) {
        if (!this.mySwiper) {
          return false;
        }

        if (type == "prev") {
          this.mySwiper.swipePrev();
        }
        if (type == "next") {
          this.mySwiper.swipeNext();
        }
      }
    },
    mounted() {
      if (this.focus_list) {
        this.createSwiper();
      }

    },
    updated() {
      this.createSwiper();
    }
  }

</script>
<style lang="scss">
  .head-slide {
    width: 100%;
    height: 900px;
    overflow: hidden;
    position: absolute;
    left: 0;
    z-index: 0;

    .head-slide-box {
      width: 100%;
      height: 100%;
      position: relative;
      overflow: hidden;
    }

    .head-slide-item {
      width: 100%;
      height: 100%;
      overflow: hidden;
      position: absolute;
      top: 0;
      left: 0;

      .slide-btn {
        position: absolute;
        top: 350px;
        width: 45px;
        height: 60px;
        background: rgba(0, 0, 0, .45);
        background-repeat: no-repeat;
        background-size: 10px 18px;
        background-position: center;
        cursor: pointer;
      }

      .slide-l {
        left: 0;
        background-image: url('../../assets/head-slide-l.png');
      }

      .slide-r {
        right: 0;
        background-image: url('../../assets/head-slide-r.png');
      }

      .slide-btn:hover {
        background-color: #31aae2;
      }

      .swiper-slide {
        // display: none;
        // opacity: 0;
        // -webkit-transition: opacity 0.8s linear;
        // -moz-transition: opacity 0.8s linear;
        // -ms-transition: opacity 0.8s linear;
        // -o-transition: opacity 0.8s linear;
        // transition: opacity 0.8s linear;
      }

      .swiper-slide-active {
        // opacity: 1;
        // display: block;
        // -webkit-transition: 300ms;
        // -moz-transition: 300ms;
        // -ms-transition: 300ms;
        // -o-transition: 300ms;
        // transition: 300ms;
      }
    }

    .head-slide-img {
      width: 100%;
      height: 900px;

      .indicators {
        bottom: 0;
        top: 555px;
        height: 13px;
        width: auto;
      }

      .slider-indicator-icon {
        width: 13px;
        height: 13px;
        background: #939da4;
        margin: 0 4px;
      }

      .slider-indicator-active {
        background: #31aae2;
        width: 45px;
        border-radius: 13px;
        transition: width ease-out 0.4s;
      }

      .btn {
        width: 44px;
        height: 60px;
        top: 350px;
        background-color: rgba(0, 0, 0, .45);
      }

      .slider-icon {
        border-left: 2px solid hsl(240, 33%, 99%);
        border-bottom: 2px solid hsla(100, 0%, 100%, 1);
      }
    }

    .slide-img {
      background: url('../../assets/head-slide1.png') no-repeat;
      background-size: cover;
      background-position: center 0;
    }

    .slide-img1 {
      background: #8db5ff;
      background-size: cover;
      background-position: center 0;
    }

    .slide-img2 {
      background: #74f7f7;
      background-size: cover;
      background-position: center 0;
    }

    // .pagination {
    //   position: absolute;
    //   z-index: 20;
    //   bottom: 10px;
    //   width: 100%;
    //   text-align: center;
    // }

  }

  .head-slide-pagination {
    position: absolute;
    top: 550px;
    left: 50%;
    z-index: 999;

    .swiper-pagination-switch {
      display: inline-block;
      width: 13px;
      height: 13px;
      border-radius: 13px;
      background: #939da4;
      margin: 0 4px;
      cursor: pointer;
      transition: width linear 0.5s;
    }

    .swiper-active-switch {
      background: #31aae2;
      width: 45px;
      border-radius: 13px;
      transition: width linear 0.5s;
    }
  }

</style>

<template>
  <div class="pet-intro">
    <div class="pet-intro-box">
      <div class="pet-tit"></div>
      <div class="pet-slide-con">
        <div class="buttle-screen btle-srol">
          <p class="btle-1">{{ bulletScreen[0] }}</p>
          <p class="btle-2">{{ bulletScreen[1] }}</p>
          <p class="btle-3">{{ bulletScreen[2] }}</p>
          <p class="btle-4">{{ bulletScreen[3] }}</p>
          <p class="btle-5">{{ bulletScreen[4] }}</p>
          <!-- <p class="btle-6">{{ bulletScreen[5] }}</p> -->
        </div>
        <swiper v-if="petSwiperOption" :options="petSwiperOption" ref="petSwiper" class="pet-swiper">
          <swiper-slide v-for="(item, index) in pet.pets" :key="index">
            <div class="pet-l">
              <!-- <img class="swiper-lazy" src="" :data-src="pet.petDec+(index + 1) +'.png'" alt="icon"> -->
              <div class="swiper-lazy"  :data-background="pet.petDec+(index + 1) +'.png'"></div>
              <!-- <div class="swiper-lazy-preloader"></div> -->
            </div>
            <div class="pet-r">
              <!-- <img src="../../assets/images/home/2.美人鱼.gif" alt="icon"> -->
              <!-- <img class="swiper-lazy" src="" :data-src="pet.petGif + (index + 1) + '.gif'" alt="icon"> -->
              <div class="swiper-lazy" :data-background="pet.petGif + (index + 1) + '.gif'"></div>
            </div>
          </swiper-slide>
        </swiper>
      </div>
      <swiper v-if="petIconOption" :options="petIconOption" ref="petIconSwiper" class="pet-icon-swiper">
        <swiper-slide v-for="(item, index) in pet.pets" :key="index">
          <img class="swiper-lazy" :data-src="pet.petIcon + (index+1) + '.png'" alt="icon">
        </swiper-slide>
      </swiper>
    </div>
    <div class="next-arrow" @click="toNext"></div>
  </div>
</template>
<script>
import { swiper, swiperSlide } from 'vue-awesome-swiper'
import { PET_INTRD } from '../../globalConf/index.js'
//底部图标

export default {
  name: 'petIntro',
  props: ['toNext'],
  components: {
    swiper,
    swiperSlide
  },
  data() {
    return {
      pet: PET_INTRD,//图片、gif路径、弹幕
      bulletScreen: '',
      petSwiperOption: '',
      petIconOption: {
        lazy: true,
        spaceBetween: 16,
        slidesPerView: 4,
        freeMode: true,
        watchSlidesVisibility: true,
        watchSlidesProgress: true,
        // virtual: true,//开启虚拟Slide功能,只渲染当前可见的slide和前后的slide
        // virtual: {
        //   slides: (function () {
        //     var slides = [];
        //     for (var i = 0; i < 20; i += 1) {
        //       slides.push('Slide ' + (i + 4));
        //     }
        //     return slides;
        //   }()),
        // }
      }
    }
  },
  computed: {
    petSwiper() {
      return this.$refs.petSwiper.swiper;
    },
    petIconSwiper() {
      return this.$refs.petIconSwiper.swiper;
    }
  },
  mounted() {
    this.$nextTick(() => {

      this.petSwiperOption = {
        lazy: true,
        speed: 1500,
        effect : 'fade',
        fadeEffect: {
          crossFade: true,
        },
        on: {
          init: () => {
            this.bulletScreen = this.pet.pets[0].text;
          },
          slideChangeTransitionEnd: () => {
            let index = this.petSwiper.activeIndex;
            this.bulletScreen = this.pet.pets[index].text;
          }
        },
        thumbs: {
          swiper: this.$refs.petIconSwiper.swiper
        }
         // virtual: true,//开启虚拟Slide功能,只渲染当前可见的slide和前后的slide
        // virtual: {
        //   slides: (function () {
        //     var slides = [];
        //     for (var i = 0; i < 20; i += 1) {
        //       slides.push('Slide ' + (i + 4));
        //     }
        //     return slides;
        //   }()),
        // },
      };
    });
  },
}
</script>
<style lang="scss">
.pet-intro{
  padding-top: 140px;
  height: 100%;
  background: url('../../assets/images/home/pet-intro-bg.png') no-repeat;
  background-size: cover;
  background-position: center bottom;
  .pet-intro-box{
    position: relative;
    top: 45%;
    margin-top: -440px;
  }
  .pet-tit{
    width: 632px;
    height: 97px;
    margin: 0 auto;
    background: url('../../assets/images/home/pet-tit.png') no-repeat;
    background-size: 100% 100%;
  }
  .pet-slide-con{
    position: relative;
  }
  .buttle-screen{
    >p{
      position: absolute;
      // font-size: 74px;
      display: inline-block;
      font-size: 64px;
      color: #e0d6b5;
      font-weight: 700;
      min-width: 1200px;
      margin-block-start: 10px;
      margin-block-end: 10px;
    }
    position: absolute;
    top: 0;
    left: 750px;
    width: 900px;
    height: 100%;
    // background: rgba(1,2,3, .5);
    z-index: 0;
  }
  .btle-srol{
    animation: 8s btleSrol linear infinite;
  }
  @keyframes btleSrol {
    0% { left: 1000px; }
    100% { left: -1000px; }
  }
  .btle-1{
    left: 20px;
    top: 0;
    width: 400px;
    // height: 78px;
  }
  .btle-2{
    left: 400px;
    top: 120px;
    width: 500px;
    // height: 79px;
  }
  .btle-3{
    left: 100px;
    top: 240px;
    width: 400px;
    // height: 79px;
  }
  .btle-4{
    left: 380px;
    top: 360px;
    width: 450px;
    // height: 78px;
  }
  .btle-5{
    left: -300px;
    top: 420px;
    width: 520px;
    // height: 79px;
  }
  .btle-6{
    left: 200px;
    top: 520px;
    width: 450px;
    // height: 79px;
  }
  .pet-swiper{
    width: 100%;
    height: 610px;
    .swiper-slide{
      height: 100%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 25px 0 60px;
    }
  }
  .pet-l{
    // width: 173px;
    // height: 270px;
    // background-size: auto;
    .swiper-lazy{
      width: 320px;
      height: 499px;
      background-size: 100%;
    }
    img{
      width: 314px;
    }
  }
  .pet-r{
    position: absolute;
    right: 0;
    .swiper-lazy{
      width: 552px;
      height: 772px;
      background-size: 100%;
      background-position: center;
    }
    img{
      width: 500px;
    }
  }

  //人物头像
  .pet-icon-swiper{
    height: 175px;
    width: 100%;
    .swiper-slide:nth-child(odd) {
      // background: rgba(233, 226, 135, 0.5);
    }
    .swiper-slide:nth-child(even){
      // background: rgba(92, 237, 241, 0.5);
    }
    .swiper-slide{
      border-radius: 100%;
      height: 175px;
      img{
        width: 100%;
      }
    }
    .swiper-slide.swiper-slide-thumb-active{
      // background: rgba(0 , 0, 0, 0.5);
    }
  }
}
</style>

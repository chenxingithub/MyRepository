<template>
  <div class="head-slide">
    <div class="head-slide-box">
      <div class="head-slide-item">
        <!-- <div class="head-slide-img"></div> -->
        <slider height="900px" class="head-slide-img" v-if="focus_list" animation="fade">
          <slider-item class="slide-img" v-for="(item, index) in focus_list" :key="index" :style="{'background-image': 'url('+imgDomain+'/'+item.picture+')'}">
          </slider-item>
        </slider>
      </div>

    </div>
  </div>
</template>
<script>
  import {
    Slider,
    SliderItem
  } from 'vue-easy-slider'
  import {
    mapState
  } from 'vuex'

  export default {
    name: 'headSlide',
    props: ['focus_list'],
    components: {
      Slider,
      SliderItem
    },
    computed: {
      ...mapState(['domain', 'imgDomain']),
    },
    methods: {
      customAnimation(animate) {
        return {
          beforeEnter(vm, el) {

            el.style.opacity = 0
          },
          // enter (vm, el, callback) {
          //     animate(vm.speed, (p) => {
          //         el.style.opacity = p
          //     }, callback)
          // },
          leave(vm, el, callback) {
            animate(vm.speed, (p) => {
              el.style.opacity = 1 - p
            }, callback)
          },
        }
      }
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

    .slider:hover .btn-right {
      background-color: rgba(0, 0, 0, 0.45);
    }

    .slider:hover .btn-left {
      background-color: rgba(0, 0, 0, 0.45);
    }

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
        transition: background 0s;
      }

      .btn:hover {
        background: #31aae2;
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
  }

</style>

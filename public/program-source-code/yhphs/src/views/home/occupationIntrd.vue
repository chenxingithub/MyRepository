<template>
  <div class="occupation-intro">
    <div class="occupation-intro-box">
      <div class="ocp-intrd-tit"></div>
      <div class="ocp-con">
        <div class="ocp-top"></div>
        <swiper :options="ocpIntrdSwiperOption" ref="ocpIntrdSwiper" class="ocp-intrd-container">
          <swiper-slide>
            <div class="list-l">
              <img class="dec" src="../../assets/images/home/occupationIntrd/dec1.png" alt="dec">
              <div class="video">
                <img class="video" src="https://bingniao-cdn3.oss-cn-shenzhen.aliyuncs.com/cms/yhphs/ocpa-intro/01.gif" alt="dec">
                <i class="video-play" @click="handleVideo('show', 0)"></i>
              </div>
            </div>
            <div class="list-r">
              <img src="../../assets/images/home/occupationIntrd/portrait1.png" alt="icon">
            </div>
          </swiper-slide>
          <swiper-slide>
            <div class="list-l">
                <img class="dec" src="../../assets/images/home/occupationIntrd/dec2.png" alt="dec">
                <div class="video">
                  <img class="video" src="https://bingniao-cdn3.oss-cn-shenzhen.aliyuncs.com/cms/yhphs/ocpa-intro/02.gif" alt="dec">
                  <i class="video-play" @click="handleVideo('show', 1)"></i>
                </div>
              </div>
            <div class="list-r">
              <img src="../../assets/images/home/occupationIntrd/portrait2.png" alt="icon">
            </div>
          </swiper-slide>
        </swiper>
        <div class="ocp-bottom"></div>
        <div class="ocp-pagi">
          <div class="swiper-button-prev ocp-prev" slot="button-prev"></div>
          <div class="swiper-button-next ocp-next" slot="button-next"></div>
          <swiper :options="paginationSwiperOption" ref="pagSwiper" class="pag-swiper">
            <swiper-slide>
              <img src="../../assets/images/home/occupationIntrd/portrait-icon1.png" alt="icon">
            </swiper-slide>
            <swiper-slide>
              <img src="../../assets/images/home/occupationIntrd/portrait-icon2.png" alt="icon">
            </swiper-slide>
          </swiper>
        </div>
      </div>
    </div>

    <video-modal
      :videoUrl="url"
      @handle-video="handleVideo('hide')"
      v-if="videoModalStatus == 'show'">
    </video-modal>
    <div class="next-arrow" @click="toNext"></div>
  </div>
</template>
<script>
import { swiper, swiperSlide } from 'vue-awesome-swiper'
import videoModal from '../../components/videoModal'
import { mapMutations } from 'vuex'

export default {
  name: 'occupationIntro',
  props: ['toNext'],
  components: {
    swiper,
    swiperSlide,
    videoModal
  },
  data() {
    return {
      videoModalStatus: 'hide',
      urls: [
        'https://bingniao-cdn3.oss-cn-shenzhen.aliyuncs.com/cms/yhphs/ocpa-intro/01.mp4',
        'https://bingniao-cdn3.oss-cn-shenzhen.aliyuncs.com/cms/yhphs/ocpa-intro/02.mp4',
      ],
      url: '',
      ocpIntrdSwiperOption: {
        init: false,
        speed: 850,
        loop : false,
        longSwipes: true,
        resistanceRatio : 0,//值越小抵抗越大越难将slide拖离边
        effect : 'fade',
        fadeEffect: {
          crossFade: true,
        },
        navigation: {
          nextEl: '.ocp-next',
          prevEl: '.ocp-prev',
        },
        on:{
          init:function(swiper){
            setTimeout(() => {
              let slide=this.slides.eq(0);
              slide.addClass('ocp-ani-slide');
            }, 850);
          },
          transitionStart: function(){
            for(let i=0;i<this.slides.length;i++){
              let slide=this.slides.eq(i);
              slide.removeClass('ocp-ani-slide');
            }
          },
          transitionEnd: function(){
            let slide=this.slides.eq(this.activeIndex);
            slide.addClass('ocp-ani-slide');
          },
          slideChangeTransitionStart: () => {

            if(this.pagSwiper) {
              this.pagSwiper.slideTo(this.ocpIntrdSwiper.activeIndex, 1000, false);//切换到第一个slide，速度为1秒
            }
          },
        }
      },
      paginationSwiperOption: {
        init: false,
        loop: false,
        initialSlide: 0,
        effect: 'coverflow',
        slidesPerView: 3,
        centeredSlides: true,
        coverflowEffect: {
          rotate: 0,
          stretch: 0,
          depth: 20,
          modifier: 1,
          slideShadows : false
        }
      }
    }
  },
  methods: {
    ...mapMutations(['handleVideoModal']),
    initSwiper() {
      this.ocpIntrdSwiper.init();
      this.pagSwiper.init();
    },
    handleVideo(type, index, event) {

      this.handleVideoModal({status: type, url: this.urls[index]});
    },
  },
  computed: {
    ocpIntrdSwiper() {
      return this.$refs.ocpIntrdSwiper.swiper;
    },
    pagSwiper() {
      window.pagSwiper = this.$refs.pagSwiper.swiper;
      return this.$refs.pagSwiper.swiper;
    }
  }
}
</script>
<style lang="scss">
.occupation-intro{
  padding-top: 140px;
  background-image: url('../../assets/images/home/occup-intro-bg.png');
  .occupation-intro-box{
    position: relative;
    top: 40%;
    margin-top: -360px;
  }
  .ocp-intrd-tit{
    width: 632px;
    height: 97px;
    margin: 0 auto;
    background: url('../../assets/images/home/ocpaIntrd-tit.png') no-repeat;
    background-size: 100% 100%;
    background-position: center;
  }
  .ocp-top, .ocp-bottom{
    width: 750px;
    height: 27px;
    margin: 0 auto;
  }
  .ocp-con{
    height: 625px;
    position: relative;
    .ocp-top{
      position: absolute;
      top: 50px;
      left: 50%;
      margin-left: -375px;
      background: url('../../assets/images/home/ocp-top.png') no-repeat;
      background-size: 100% 100%;
    }
    .ocp-bottom{
      margin-top: 10px;
      background: url('../../assets/images/home/ocp-bottom.png') no-repeat;
      background-size: 100% 100%;
    }
  }
  .ocp-intrd-container{
    height: 625px;
    width: 100%;
    // margin-top: 50px;
    background: url('../../assets/images/home/ocp-slide-bg.png') no-repeat;
    background-size: 750px 544px;
    background-position: center bottom;
    .list-l, .list-r{
      position: absolute;
    }
    .list-l{
      top: 100px;
      left: 50px;
      height: 460px;
      width: 276px;
      transform: translateX(-80px);
      opacity: 0;
      transition: all .4s;
      .dec{
        width: 275px;
        height: auto;
      }
      .video{
        display: block;
        position: relative;
        margin: 15px auto 0;
        width: 249px;
        height: 139px;
      }
      .video-play{
        $h: 53px;
        $w: 61px;
        display: block;
        position: absolute;
        top: 50%;
        left: 50%;
        width: $w;
        height: $h;
        margin: (-$h/2) 0 0 (-$w/2);
        background: url('../../assets/images/common/vicon.png') no-repeat;
        background-size: 100% 100%;
      }
    }
    .list-r{
      top: 0;
      right: 0;
      height: 625px;
      width: 460px;
      // background: rgba(1,2,3, .5);
      // background: url('../../assets/images/home/occupationIntrd/portrait2.png') no-repeat;
      background-repeat: no-repeat;
      background-size: auto 625px;
      background-position: right top;
      transform: translateX(80px);
      opacity: 0;
      transition: all .4s;
      img{
        width: 100%;
      }
    }
    .ocp-ani-slide{
      .list-l{
        transform: translateX(0);
        transition: all .4s ease;
        opacity: 1;
      }
      .list-r{
        transform: translateX(0);
        transition: all .4s ease;
        opacity: 1;
      }
    }
  }
  .swiper-button-prev, .swiper-button-next{
    width: 184px;
    height: 32px;
    top: 80px;
    background-size: 184px 32px;
  }
  .swiper-button-prev{
    left: 70px;
    background-image: url('../../assets/images/home/ocp-arr-l.png');
  }
  .swiper-button-next{
    right: 70px;
    background-image: url('../../assets/images/home/ocp-arr-r.png');
  }
  // .swiper-pagination{
  //   width: 230px;
  //   top: 70px;
  //   left: 50%;
  //   margin-left: -115px;
  // }
  // .swiper-pagination-bullet{
  //   width: 26px;
  //   height: 25px;
  //   border-radius: 0;
  //   background-image: url('../../assets/images/home/unselect-icon.png');
  //   background-repeat: no-repeat;
  //   background-size: 100% 100%;
  //   background-position: center;
  //   background-color: rgba(255,255,255, 0);
  // }
  // .swiper-pagination-bullet-active{
  //   width: 94px;
  //   height: 106px;
  //   background: url('../../assets/images/home/portrait1.png') no-repeat;
  //   background-size: 100% 100%;
  // }

  .ocp-pagi{//前进后退按钮块
    position: relative;
    .icon-cir{
      position: absolute;
      left: 50%;
      top: 50px;
      margin-left: -45px;
      // width: 90px;
      // height: 90px;
      z-index: 1;
      // background: url('../../assets/images/home/ocp-cir.png') no-repeat;
      background-size: 100% 100%;
      img{
        width: 84px;
        height: 102px;
      }
    }
  }
  //小图片展示
  .pag-swiper{
    width: 230px;
    top: 20px;
    left: 50%;
    margin-left: -115px;
    .swiper-slide:nth-child(1){
      background-image: url('../../assets/images/home/occupationIntrd/portrait-icon1.png');
    }
    .swiper-slide:nth-child(2){
      background-image: url('../../assets/images/home/occupationIntrd/portrait-icon2.png');
    }
    .swiper-slide{
      width: 94px;
      height: 106px;
      background-repeat: no-repeat;
      background-size: 32px 36px;
      background-position: center;
      img{
        width: 26px;
        height: 29px;
        opacity: 0;
        vertical-align: middle;
        transition: all 0.5s ease-out;
      }
    }
    .swiper-slide-active{
      background: none !important;
      img{
        width: 94px;
        height: 106px;
        opacity: 1;
        vertical-align: middle;
        transition: all 0.5s ease-in;
      }
    }
  }
}
</style>

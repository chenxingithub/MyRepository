<template>
  <div class="game-features">
    <div class="game-features-box">
      <div class="tit"></div>
      <div class="game-slide-nav flex-sb-c">
        <div class="slide-cnt">
          <p>{{ curSlide }}</p>
          <p>{{ slideTotal }}</p>
        </div>
        <div class="slide-txt">
          <p>{{ curentText.tit }}</p>
          <p>{{ curentText.dec }}</p>
        </div>
      </div>
      <swiper v-if="gameSwiperOption" :options="gameSwiperOption" ref="gameSwiper" class="game-swiper">
        <swiper-slide v-for="(item, index) in slides" :key="index">
          <div class="sld-box" :style="{'background-image': `url(https://bingniao-cdn3.oss-cn-shenzhen.aliyuncs.com/cms/yhphs/game-feau/slide${index+1}/bg.png)`}">
            <div class="s-1-1 slide">
              <img class="g-icon" @click="handleClickIcon" src="../../../static/localImg/game-features/acl-1.png"
                :data-img="`https://bingniao-cdn3.oss-cn-shenzhen.aliyuncs.com/cms/yhphs/game-feau/slide${index+1}/01.gif`" alt="acl">
              <div class="g-line"></div>
            </div>
            <div class="s-1-2 slide">
              <img class="g-icon"  @click="handleClickIcon" src="../../../static/localImg/game-features/acl-2.png"
                :data-img="`https://bingniao-cdn3.oss-cn-shenzhen.aliyuncs.com/cms/yhphs/game-feau/slide${index+1}/02.gif`" alt="acl">
              <div class="g-line"></div>
            </div>
            <div class="s-1-3 slide">
              <img class="g-icon"  @click="handleClickIcon" src="../../../static/localImg/game-features/acl-3.png"
                :data-img="`https://bingniao-cdn3.oss-cn-shenzhen.aliyuncs.com/cms/yhphs/game-feau/slide${index+1}/03.gif`" alt="acl">
              <div class="g-line"></div>
            </div>
          </div>
        </swiper-slide>
      </swiper>
      <div class="swiper-button-prev game-prev" slot="button-prev"></div>
      <div class="swiper-button-next game-next" slot="button-next"></div>
      <div class="game-swiper-pagination"  slot="pagination"></div>
    </div>
    <div class="next-arrow" @click="toNext"></div>
  </div>
</template>
<script>
import { swiper, swiperSlide } from 'vue-awesome-swiper'

export default {
  name: 'gameFeatures',
  props: ['toNext'],
  components: {
    swiper,
    swiperSlide
  },
  data() {
    return {
      slides: (new Array(4)).fill(0),
      slideTotal: '03',
      curSlide: '01',
      textOfficia: [
        {
          tit: '龙骑萌宠',
          dec: '百变幻化养成'
        },
        {
          tit: '魔法大陆',
          dec: '极致西式幻想'
        },
        {
          tit: '千面副本',
          dec: '资源自由交易'
        },
        {
          tit: '史诗巨兽',
          dec: '畅快刷怪体验'
        }
      ],
      curentText: {
        tit: '',
        dec: ''
      },
      gameSwiperOption: {
        speed: 800,
        init: false,
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
        pagination: {
          el: '.game-swiper-pagination',
        },
        // pagination: {
        //   el: '.game-swiper-pagination1',
        // },
        on:{
          init: () => {
            this.slideTotal = this.handleIndex(this.gameSwiper.slidesGrid.length);

            setTimeout(() => {//初始化完成，节点渲染结束后添加class执行动画
              let gifUrl = 'https://bingniao-cdn3.oss-cn-shenzhen.aliyuncs.com/cms/yhphs/game-feau/slide1/01.gif';
              let slide = this.gameSwiper.slides.eq(0);
              let icon = document.getElementsByClassName('g-icon')[0];

              slide.addClass('g-active');
              // icon.style.cssText = "transition-delay: 0.3s";
              gifUrl = icon.getAttribute('data-img');
              icon.classList.add('gif');
              icon.setAttribute('src', gifUrl);
            },250);
          },
          transitionStart: function(){
            for(let i=0;i<this.slides.length;i++){
              let slide=this.slides.eq(i);
              slide.removeClass('g-active');
            }
          },
          transitionEnd: () => {
            let slide=this.gameSwiper.slides.eq(this.gameSwiper.activeIndex);
            this.curSlide = this.handleIndex(this.gameSwiper.realIndex + 1);
            slide.addClass('g-active');
            this.curentText = this.textOfficia[this.gameSwiper.activeIndex];
          },
        }
      }
    }
  },
  methods: {
    initSwiper() {
      this.gameSwiper.init();
    },
    handleIndex(num) {
      num = Number(num);
      if(num < 10) {
        return "0"+num;
      } else {
        return num;
      }
    },
    handleClickIcon(e) {

      let dom = e.target;
      let gifUrl = 'https://bingniao-cdn3.oss-cn-shenzhen.aliyuncs.com/cms/yhphs/game-feau/slide1/01.gif';
      let iconUrl = 'https://bingniao-cdn3.oss-cn-shenzhen.aliyuncs.com/cms/yhphs/game-feau/s-1-1.png';
      dom.setAttribute('src', dom.getAttribute('data-img'));

      let nodeLists = document.getElementsByClassName('gif');
      for(let i=0,len=nodeLists.length; i < len; i++) {
        if(nodeLists[i] == dom) {
          return false;
        }
        // iconUrl = nodeLists[i].getAttribute('data-img');
        // console.log(iconUrl);
        nodeLists[i].setAttribute('src', iconUrl);
        nodeLists[i].classList.remove('gif');
      }

      dom.classList.add('gif');
    }
  },
  computed: {
    gameSwiper() {
      return this.$refs.gameSwiper.swiper;
    }
  },
  mounted() {
    // alert(navigator.connection.effectiveType);
    this.$nextTick(() => {
      // this.slideTotal = this.handleIndex(this.gameSwiper.slidesGrid.length);
    });
  }
}
</script>
<style lang="scss">
.game-features{
  // height: 1085px;
  padding-top: 140px;
  background-image: url('../../assets/images/home/game-features-bg.png');
  .game-features-box{
    position: relative;
    top: 40%;
    margin-top: -405px;
  }
  .tit{
    width: 632px;
    height: 97px;
    margin: 0 auto;
    background: url('../../assets/images/home/game-feat-tit.png') no-repeat;
    background-size: 100% 100%;
  }
  .game-slide-nav{
    width: 750px;
    height: 150px;
    padding: 0 22px 0 19px;
  }
  .slide-cnt{
    width: 88px;
    color: #000000;
    font-size: 40px;
    // font-weight: 600;
    p{
      margin-block-start: 4px;
      margin-block-end: 4px;
      text-align: center;
    }
    >p:nth-child(1){
      border-bottom: 4px solid #a99043;
    }
  }
  .slide-txt{
    width: 400px;
    height: 95px;
    padding-right: 20px;
    text-align: right;
    border-right: 10px solid #a99043;
    >p{
      // margin: 20px 0;
      margin-block-start: 4px;
      margin-block-end: 4px;
    }
    >p:nth-child(1){
      color: #000000;
      font-size: 44px;
      font-weight: 600;
    }
    >p:nth-child(2){
      color: #242437;
      font-size: 34px;
    }
  }

  .game-swiper{
    height: 590px;
    width: 100%;
    margin-top: -25px;
  }
  .swiper-slide{
    height: 100%;
    >div{
      margin: 50px 0;
      position: relative;
      width: 100%;
      height: 500px;
      background-size: 710px 490px;
      background-position: center;
      background-repeat: no-repeat;
    }
  }
  .slide{
    position: absolute;
    .g-icon{
      width: 91px;
      height: 91px;
      box-shadow: 0 0 0 2px #ffffff inset;
      border-radius: 100%;
    }
  }
  .s-1-1{
    top: 33px;
    left: 194px;
    width: 198px;
    height: 115px;
    .g-icon{
      position: absolute;
      left: 0;
      top: 0;
      z-index: 10;
      visibility: hidden;
      transform: scale(1);
      transition: transform 2s ease-out;
    }
    .g-line{
      position: absolute;
      z-index: 0;
      right: 0;
      bottom: 0;
      margin-top: 45px;
      width: 0;//111px
      height: 70px;
      background: url('../../../static/localImg/game-features/line-1.png') no-repeat;
      background-size: 111px 70px;
      background-position: right bottom;
      transition: width 0.4s linear 0.2s;
    }
  }
  .s-1-2{
    width: 200px;
    height: 130px;
    right: 126px;
    top: 120px;
    .g-icon{
      position: absolute;
      right: 0;
      top: 0;
      z-index: 1;
      visibility: hidden;
      transform: scale(1);
      transition: scale 0.2s ease-in;
    }
    .g-line{
      position: absolute;
      left: 0;
      top: 0;
      margin-top: 45px;
      width: 0;//111px
      height: 84px;
      background: url('../../../static/localImg/game-features/line-2.png') no-repeat;
      background-size: 111px 84px;
      transition: width 0.4s linear 0.2s;
      // background-position: right bottom;
    }
  }
  .s-1-3{
    left: 88px;
    top: 274px;
    width: 247px;
    height: 101px;
    .g-icon{
      position: absolute;
      left: 0;
      top: 0;
      z-index: 1;
      visibility: hidden;
      transform: scale(1);
      transition: transform 0.2s linear;
    }
    .g-line{
      position: absolute;
      right: 0;
      top: 0;
      margin-top: 45px;
      width: 0;//111px
      height: 53px;
      background: url('../../../static/localImg/game-features/line-3.png') no-repeat;
      background-size: 158px 53px;
      transition: width 0.4s linear 0.2s;
      background-position: right bottom;
    }
  }
  .g-active{
    .g-line{
      width: 160px;
      transition: width 0.5s linear;
    }
    .g-icon{
      visibility: visible;
      // transition: all 0.2s linear;
      transition: visibility 0.2s linear 0.55s;
    }
  }
  .slide{
    .gif{
      // width: 239px;
      // height: 239px;
      border: 1px solid #ffffff;/*no*/
      // padding: 1px;
      border-radius: 100%;
      transform: scale(2.8);
      transition: transform 0.3s linear 0.3s;
    }
  }

  @keyframes line {
    from {width: 0;}
    to {width: 111px;}
  }
  .swiper-button-next,.swiper-button-prev{
    width: 24px;
    height: 38px;
    top: 840px;
    background-size: 24px 38px;
  }
  .swiper-button-prev{
    left: 150px;
    background-image: url('../../assets/images/home/game-ar-l.png');
  }
  .swiper-button-next{
    right: 150px;
    background-image: url('../../assets/images/home/game-ar-r.png');
  }

  .game-swiper-pagination{
    text-align: center;
    margin-top: 10px;
  }
  .swiper-pagination-bullet{
    width: 28px;
    height: 28px;
    opacity: 1;
    margin: 0 10px;
    background: url('../../assets/images/home/game-pgn.png') no-repeat;
    background-size: 100% 100%;
  }
  .swiper-pagination-bullet-active{
    background-image: url('../../assets/images/home/game-pgn-sld.png');
  }
}
</style>

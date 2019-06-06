<template>
  <div class="news-info">
    <div class="news-info-box">
      <div class="tit"></div>
      <div class="news-slide"></div>
      <swiper :options="newsPicSwiperOption" ref="newsPicSwiper" class="news-pic-slide">
        <swiper-slide class="">
          <a href="javascript:;">
            <div></div>
          </a>
        </swiper-slide>
        <swiper-slide>
          <a href="javascript:;">
            <div></div>
          </a>
        </swiper-slide>
        <swiper-slide>
          <a href="javascript:;">
            <div></div>
          </a>
        </swiper-slide>
      </swiper>
      <div class="news-list-nav">
        <div class="news-swiper-pagination"  slot="pagination"></div>
        <router-link :to="{path: '/articleList', query: {artListId: 74, listId: 74}}" tag="div" class="list-more">+</router-link>
      </div>
      <swiper :options="newsListSwiperOption" ref="newsListSwiper" class="news-list-slide">
        <swiper-slide v-for="(items, index) in lists" :key="index">
        <router-link :to="{path: '/Article', query: {artId: item.id}}" v-for="(item, index) in items.data[0].data" :key="index" class="flex-sb item-box">
          <div class="item">
            <span class="dot"></span>&nbsp;{{ item.title }}
            </div>
          <span class="time">{{ item.updated_at.slice(5,10) }}</span>
        </router-link>
        </swiper-slide>
      </swiper>
    </div>
    <div class="next-arrow" @click="toNext"></div>
  </div>
</template>
<script>
import { swiper, swiperSlide } from 'vue-awesome-swiper'
import { request_articleList } from '../../api/index.js'

export default {
  name: 'newInfo',
  props: ['toNext'],
  components: {
    swiper,
    swiperSlide
  },
  data() {
    return {
      lists: [],
      newsPicSwiperOption: {//新闻资讯图片轮播参数
        init: false,
        speed: 850,
        loop : true,
        resistanceRatio : 0,//值越小抵抗越大越难将slide拖离边
        autoplay: {
          delay: 3000,
          stopOnLastSlide: false,
          disableOnInteraction: true,
        },
        on: {
          init: () => {
            setTimeout(() => {
              let slide=this.newsListSwiper.slides.eq(0);
              slide.addClass('slidePicAni');
              document.getElementsByClassName('news-list-nav')[0].classList.add('news-nav-act');
            }, 550);
          }
        }
      },
      newsListSwiperOption: {
        init: false,
        loop : false,
        on: {
          init: () => {
            setTimeout(() => {
              let slide=this.newsListSwiper.slides.eq(0);
              slide.addClass('slideAni');
            }, 550);
          }
        },
        pagination: {
          el: '.news-swiper-pagination',
          clickable: true,
          renderBullet: function (index, className) {
              let text = '';
              switch(index){
                  default: text='';
                  case 0:text='最新';break;
                  case 1:text='新闻';break;
                  case 2:text='公告';break;
                  case 3:text='攻略';break;
              }
              return '<div class="' + className + '">' + text + '</div>';
          },
        }
      }
    }
  },
  methods: {
    initSwiper() {
      this.newsPicSwiper.init();
      this.newsListSwiper.init();
    }
  },
  computed: {
    newsPicSwiper() {
      return this.$refs.newsPicSwiper.swiper;
    },
    newsListSwiper() {
      return this.$refs.newsListSwiper.swiper;
    }
  },
  created(){
    let limit = 4,
        page = 1,
        ids = '74,75,76,77';
    request_articleList(ids, page, limit)
      .then(res => {
        this.lists = res.data;
      })
  },
  mounted() {
    // this.initSwiper();
  }
}
</script>
<style lang="scss">
.news-info{
  padding-top: 140px;
  // background: rgba(1,2,3, .4);
  background-image: url('../../assets/images/common/news-info-bg.png');
  .tit{
    width: 632px;
    height: 97px;
    margin: 0 auto;
    background: url('../../assets/images/home/news-info-tit.png') no-repeat;
    background-size: 100% 100%;
    background-position: center;
  }
  .news-info-box{
    position: relative;
    top: 45%;
    margin-top: -431px;
  }
  .news-pic-slide{
    width: 710px;
    height: auto;
    margin: 28px auto;
    .swiper-slide {
      height: 350px;
      >a{
        display: block;
        box-sizing: border-box;
        // height: 100%;
        // width: 100%;
        padding: 10px 0;
        background: url('../../assets/images/home/news-slide.png') no-repeat;
        background-size: 100% 100%;
        background-position: center;
        >div{
          width: 690px;
          height: 330px;
          margin: 0 auto;
          border: 1px solid #8d8d95;
        }
      }
    }
    .slidePicAni{
      >a{
        animation: aniMoveUp 1.2s ease;
      }
    }
  }
  .news-list-nav{//新闻资讯导航栏
    position: relative;
    border-bottom: 1px solid #d3a718;
    opacity: 0;
    .list-more{
      position: absolute;
      top: 12px;
      right: 32px;
      width: 32px;
      height: 32px;
      line-height: 32px;
      font-size: 40px;
      background: #d3a718;
      text-align: center;
      color: #ffffff;
    }
  }
  .news-list-nav:before{
    display: block;
    content: '';
    position: absolute;
    bottom: -7px;
    left: 0;
    width: 750px;
    height: 3px;
    background: #d3a718;
  }
  .news-nav-act{//动画类
    opacity: 1;
    animation: aniMoveUp 0.8s ease;
  }
  .news-swiper-pagination{//新闻资讯导航栏
    .swiper-pagination-bullet{
      width: 137px;
      height: 60px;
      line-height: 60px;
      font-size: 30px;
      border-radius: 0;
      color: #242437;
      text-align: center;
      background: none;
      opacity: 1;
      margin: 0 5px;
    }
    .swiper-pagination-bullet-active{
      color: #c09522;
      border: none;
    }
  }
  .news-list-slide{
    width: 750px;
    height: 298px;
    font-size: 28px;
    margin-top: 2px;
    // border-top: 2px solid #d3a718;
    .swiper-slide:nth-child(1){
      >div{
        opacity: 0;
      }
    }
    .swiper-slide{
      padding: 0 20px;
      height: 100%;
      .item-box{
        color: #000000;
        box-sizing: border-box;
        height: 98px;
        width: 710px;
        font-size: 28px;
        line-height: 130px;
        padding: 0 10px 0 14px;
        border-bottom: 1px solid #d1d1d6;
        position: relative;
        overflow: visible;
        // opacity: 0;
      }
      .item-box::before{
        position: absolute;
        content: '';
        bottom: -2px;
        left: 10px;
        width: 101px;
        height: 3px;
        z-index: 1;
        background: #d3a718;
      }
      .time{
        color: #d3a718;
      }
      .item{
        width: 530px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
      }
      .dot{
        display: inline-block;
        width: 8px;
        height: 8px;
        background: #d3a718;
        vertical-align: middle;
      }
    }
  }
  .slideAni{
    animation: aniMoveUp 0.8s ease;
    >div{
      opacity: 1 !important;
      // animation: aniMoveUp 0.8s ease;
      // >div{
      //   animation: aniMoveUp 1.2s ease;
      // }
      // >span{
      //   animation: aniMoveUp 1.2s ease;
      // }
    }
    // >div:nth-child(2){
    //   >div{
    //     animation-delay: 0.1s;
    //   }
    //   >span{
    //     animation-delay: 0.1s;
    //   }
    // }
    // >div:nth-child(3){
    //   >div{
    //     animation-delay: 0.2s;
    //   }
    //   >span{
    //     animation-delay: 0.2s;
    //   }
    // }
  }

  @keyframes aniMoveUp {
    from {
      opacity: 0;
      transform: translateY(60px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
}
</style>

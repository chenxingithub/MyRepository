<template>
  <div id="newsInfo">
    <div class="newsInfo-content">
      <div class="newsInfo">
        <ul class="tit clearfix">
          <li @mouseover="exchangeList(article_ids[0])" :class="{active: activeId == article_ids[0]}">综合</li>
          <li @mouseover="exchangeList(article_ids[1])" :class="{active: activeId == article_ids[1]}">公告</li>
          <li @mouseover="exchangeList(article_ids[2])" :class="{active: activeId == article_ids[2]}">攻略</li>
          <li @mouseover="exchangeList(article_ids[3])" :class="{active: activeId == article_ids[3]}">活动</li>
          <router-link :to="{path: '/articleList', query: {listId: article_ids[0]}}" tag="li">
            <img src="../../assets/images/news-more-icon.png" alt="more">
          </router-link>
        </ul>
        <ul class="items-box clearfix" v-if="articleList">
          <li v-for="(item, index) in articleList.data[0].data" :key="index" v-if="index < 5" class="items">
            <router-link :to="{path: '/article', query: {articleId: item.id}}" v-if="!item.jump_url">
              <p class="time">{{ item.updated_at.split(' ')[0].substr(5).split('-').reverse().join('/') }}</p>
              <div class="dec">
                {{ item.title }}
              </div>
            </router-link>
            <a :href="item.jump_url" v-if="item.jump_url" target="_blank">
              <p class="time">{{ item.updated_at.split(' ')[0].substr(5).split('-').reverse().join('/') }}</p>
              <div class="dec">
                {{ item.title }}
              </div>
            </a>
          </li>
        </ul>
        <div class="items-more">
          <router-link v-if="articleList"
          :to="{path: '/articleList', query: {listId: activeId}}" tag='span'>
          <img src="../../assets/images/list-more-bg.png" alt=""></router-link>
        </div>
        <div class="items-slide newsInfo-slide swiper-container">
            <div class="swiper-wrapper">
              <!-- <div class="swiper-slide"><img src="../../assets/images/slide1.png" alt=""></div> -->
              <div class="swiper-slide" v-if="focus_lists"
                v-for="(item, index) in handleFocusLists"
                :key="index" :style="{'background-image': 'url('+ imgDomain+'/'+item.picture +')'}">
                <a :href="item.jump_url" target="_blank"></a>
              </div>
              <!-- <div class="swiper-slide"></div> -->
            </div>
            <div class="pagination tems-slide-pagination"></div>
        </div>
      </div>
    </div>
    <div class="arrow-next" @click="$emit('slideNext')"></div>
  </div>
</template>
<script>
import { mapState } from 'vuex'
import Swiper from '../../plugins/swiper/idangerous.swiper2.7.6.js'
import '../../plugins/swiper/idangerous.swiper2.7.6.css'

export default {
  name: 'NewsInfo',
  props: [
    'article_lists',
    'article_ids',
    'focus_lists'
  ],
  data() {
    return {
      swiper: null,
      articleList: null,
      activeId: 73,// 默认所有文章
      focusLists: null
    };
  },
  computed: {
    ...mapState([
      'imgDomain'
    ]),
    handleFocusLists() {
      if(!this.focus_lists) { return false; }

      return this.focus_lists[1];
    }
  },
  methods: {
    exchangeList(id) {//拿到父组件传过来的文章列表
      if(!this.article_lists) { return false; }
      if(!this.articleList) {
        this.articleList = this.article_lists[this.article_ids[0]];
        return false;
      }
      this.articleList = this.article_lists[id];
      this.activeId = id;
    }
  },
  mounted() {

    this.exchangeList();
  },
  updated() {
    this.$nextTick(() => {
      if(!this.article_lists) { return false; }

      if(!this.articleList) {
        this.articleList = this.article_lists[this.article_ids[0]];
        this.activeId = this.article_ids[0];
      }
      if(!this.focus_lists) {
        this.focusLists = this.focus_lists[2];//焦点图数据源为移动端
      }

      // this.swiper.reInit();//请求轮播的数据返回后后重新初始化swiper对象
      if(!this.swiper) {
        this.swiper = new Swiper(".newsInfo-slide", {
        pagination: '.tems-slide-pagination',
        paginationClickable: true,
        onlyExternal : true,
        autoplay : 3000,
        speed:300,
        loop: true
      });
      }
      // console.log('updated');
    });

  },
  watch: {
    'article_lists': 'exchangeList'
  }
}
</script>
<style lang="scss">
#newsInfo{
  width: 100%;
  height: 1080px;
  @include bg('../../assets/images/news-info-bg.jpg', 1920px, 1080px, center, center);
  .newsInfo-content{
    width: 1200px;
    height: 512px;
    position: absolute;
    top: 268px;
    left: 50%;
    margin-left: -(1200px/2);
    // background: rgba(138, 190, 241, 0.3);
    .newsInfo{
      position: absolute;

      .tit{
        box-sizing: border-box;
        border-bottom: 2px solid #b9b9b9;
        width: 628px;
        height: 60px;
        li{
          float: left;
          list-style: none;
          color: #b9b9b9;
          font-size: 28px;
          width: 76px;
          height: 60px;
          line-height: 60px;
          margin: 0 30px;
          text-align: center;
          box-sizing: border-box;
          transition: all 0.3s linear;
        }
        li.active{
          color: #970e0f;
          border-bottom: 2px solid #970e0f;
          transition: all 0.3s linear;
        }
        li:first-child{
          margin: 0;
          text-align: left;
        }
        li:last-child{
          width: 83px;
          text-align: right;
          line-height: 61px;
          margin: 0 0 0 60px;
        }
        li:last-child:hover{
          border: none;
          cursor: pointer;
        }
      }
      .items-box{
        margin-top: 38px;
        width: 1200px;
        height: 378px;
        p{
          font-size: 28px;
          color: #292b2b;
          height: 34px;
          border-bottom: 2px solid #292b2b;
          transition: color 0.3s linear;
          font-weight: 600;
        }
        .dec{
          margin-top: 14px;
          height: 76px;
          overflow: hidden;
          line-height: 26px;
          text-align: justify;
          color: #adadad;
          box-sizing: border-box;
          padding: 0 10px;
        }
        .items{
          transition: color 0.25s;
          width: 306px;
          height: 175px;
          float: left;
          // border: 2px solid #292b2b;
          box-sizing: border-box;
          padding: 20px;
          color: #cecece;
          font-size: 18px;
          list-style: none;
          box-shadow: inset 0 0 0 2px #292b2b;
          position: relative;
          transition: color 0.25s;
          display: block;
          margin: 14px 14px 0 0;
          cursor: pointer;
          background: rgba(0,0,0,.35);
          transition: background 0.3s linear;
          a{
            position: absolute;
            z-index: 10;
            width: 260px;
            padding: 0 10px;
          }
        }
        .items:nth-child(2){
          margin-right: 570px;
        }
        .items:nth-child(3){
          margin-left: 318px;
        }
        .items:nth-child(5){
          width: 242px;
          margin-right: 0;
          a{
            width: 200px;
          }
        }
        .items:hover{
          background: #000000;
          transition: color 0.3s linear;
          p{
            border-bottom: 2px solid #000000;
            color: #970e0f;
            transition: all 0.3s linear;
          }
        }
        .items::before, .items::after{
          border: 2px solid transparent;
          width: 0;
          height: 0;
          box-sizing: border-box;
          position: absolute;
          content: '';
        }
        .items::before{
          top: 0;
          left: 0;
        }
        .items::after{
          bottom: 0;
          right: 0;
        }
        .items:hover::before, .items:hover::after{
          width: 100%;
          height: 100%;
          content: '';
          position: absolute;
        }
        $time: 0.25s;
        .items:hover::before{
          border-top-color: #970e0f;
          border-right-color: #970e0f;
          transition: width $time ease-out, height $time ease-out $time;
        }
        .items:hover::after {
          border-bottom-color: #970e0f;
          border-left-color: #970e0f;
          transition: border-color 0s ease-out $time*2, width $time ease-out $time*2, height $time ease-out $time*3;
        }
      }
      .items-more{//查看跟多
        position: absolute;
        top: 301px;
        left: 0;
        width: 306px;
        height: 175px;
        line-height: 204px;
        // background: rgba(118, 181, 245, 0.35);
        font-size: 60px;
        font-weight: 600;
        text-align: center;
        cursor: pointer;
        span{
          // background-image: -webkit-gradient(linear, 0 0, 0 bottom, from(rgb(188, 190, 190)), to(rgba(73, 76, 76, 1)));
          // -webkit-background-clip: text;
          // -webkit-text-fill-color: transparent;
        }
      }
      .items-slide{//轮播图
        position: absolute;
        top: -40px;
        right: 0;
        width: 560px;
        height: 326px;
        // background: rgba(118, 181, 245, 0.35);
        border: #b9b9b9 1px solid;
        .swiper-slide{
          @include bg('../../assets/images/slide1.png', 100%, 100%, center, center);
          // background-size: initial;
          // img{
          //   height: 100%;
          //   position: absolute;
          //   left: 50%;
          //   margin-left: -350px;
          // }
          a{
            display: block;
            width: 100%;
            height: 100%;
          }
        }
        .pagination {
          position: absolute;
          z-index: 20;
          bottom: 10px;
          right: 100px;
          width: 100%;
          text-align: right;
        }
        .swiper-pagination-switch {
          display: inline-block;
          width: 5px;
          height: 5px;
          // border-radius: 8px;
          background: #010000;
          margin: 0 3px;
          opacity: 0.8;
          // border: 1px solid #fff;
          cursor: pointer;
        }
        .swiper-active-switch {
          background: #a60101;
        }
      }
    }
  }
  .arrow-next{
    width: 67px;
    height: 45px;
    position: absolute;
    bottom: 30px;
    left: 50%;
    margin-left: -34px;
    z-index: 100;
    cursor: pointer;
    @include bg('../../assets/images/arrow-down.png');
  }
}
</style>

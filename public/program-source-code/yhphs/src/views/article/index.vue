<template>
  <div class="article">
    <head-nav></head-nav>
    <div class="nav flex-c">
      <div class="back" @click="back">
        <a href="javascript:;"></a>
      </div>
      <div class="path">
        <router-link to="/" tag="span">首页</router-link> > <span @click="back">新闻资讯</span> >
      </div>
      <div class="nav-bar"></div>
    </div>
    <div class="content" v-if="article">
      <div class="tit">
        <p>{{article.title}}</p>
        <p>发表于 {{article.updated_at.slice(0, -3)}}</p>
      </div>
      <div class="text" v-html="article.content">
      </div>
    </div>
    <foot-nav :toTop="scrollTop"></foot-nav>
  </div>
</template>
<script>
import headNav from '../../components/head/index'
import footNav from '../../components/foot/index'
import { request_article } from '../../api/index.js'
import { scrollToTop } from '../../utils/utils.js';

export default {
  name: 'Article',
  components: {
    headNav,
    footNav
  },
  data() {
    return {
      article: ''
    }
  },
  methods: {
    back() {
      this.$router.go(-1);
    },
    req_article(id) {
      request_article(id)
        .then(res => {
          this.article = res.data.data;
        });
    },
    scrollTop() {
      scrollToTop();
    },
  },
  created() {

  },
  activated() {
     this.req_article(this.$route.query.artId);
  }
}
</script>
<style lang="scss">
.article{
  overflow: hidden;
  padding-top: 100px;
  .nav{
    width: 750px;
    height: 88px;
    background-color: #ffffff;
    position: relative;
  }
  .nav-bar{
    position: absolute;
    top: 74px;
    left: 0;
    width: 750px;
    height: 37px;
    background: url('../../assets/images/common/head-icon.png') no-repeat;
    background-size: 750px 37px;
  }
  $mgTop: -5px;//头部内容上偏移
  .back{
    width: 200px;
    height: 39px;
    margin-top: $mgTop;
    border-right: 2px solid #d4b355;
    >a{
      width: 150px;
      height: 39px;
      display: block;
      margin: 0 auto;
      background: url('../../assets/images/common/back.png') no-repeat;
      background-size: 128px  22px;
      background-position: center center;
    }
  }
  .path{
    margin-top: $mgTop;
    padding-left: 52px;
    color: #242437;
    font-size: 24px;
  }
  .content{
    padding: 30px 36px;
    margin-top: -16px;
    min-height: 800px;
    // background: url('../../assets/images/article/bg.png') no-repeat;
    // background-size: 750px 1334px;
    // background-position: center top;
    // background-color: #ffffff;
    .tit{
      text-align: center;
      >p:nth-child(1){
        font-size: 38px;
        color: #242437;
        font-weight: 600;
      }
      >p:nth-child(2){
        font-size: 24px;
        color: #b1b1b8;
      }
    }
    .text{
      position: relative;
      border-top: 1px solid #ebebee;
    }
    .text:before{
      position: absolute;
      top: -4px;
      left: 50%;
      margin-left: -30px;
      content: '';
      width: 60px;
      height: 2px;
      background: #d4b355;
    }
  }
}
.article:before{
  content: ' ';
  position: fixed;
  z-index: 0;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  z-index: -1;
  background:  url('../../assets/images/article/bg.png') no-repeat center bottom;
  background-size: 100% auto;
}
</style>

<template>
  <div class="article-list" ref="articleListNode">
    <!-- <head-nav></head-nav> -->
    <div class="nav flex-c">
      <div class="back">
        <!-- <a href="javascript:;" @click="back"></a> -->
        <router-link to="/"></router-link>
      </div>
      <div class="nav-list flex-sa-c">
        <router-link :class="{'item': true, 'item-act': handleRouteChange == item.id}"
          v-for="(item, index) in createListNav" @click.native="handleListChange(item.id)"
          :key="index" :to="{path: 'articleList', query: {artListId: item.p_id, listId: item.id}}">
          {{ item.name }}
        </router-link>
      </div>
      <div class="nav-bar"></div>
    </div>
    <div class="list">
      <div class="item" href="javascript:;"
        v-for="(item, index) in artLists" :key="index"
        >
        <a :href="item.jump_url" class="flex-c" v-if="item.jump_url">
          <div class="time">
            <p>{{ item.updated_at.slice(5,10) }}</p>
            <p>{{ item.updated_at.slice(0,4) }}</p>
          </div>
          <div class="txt">
            <p>{{ item.title }}</p>
            <p>{{ item.subtitled }}</p>
          </div>
        </a>
        <router-link class="flex-c" :to="{path: '/Article', query: {artId: item.id}}" v-if="!item.jump_url" >
          <div class="time">
            <p>{{ item.updated_at.slice(5,10) }}</p>
            <p>{{ item.updated_at.slice(0,4) }}</p>
          </div>
          <div class="txt">
            <p>{{ item.title }}</p>
            <p>{{ item.subtitled }}</p>
          </div>
        </router-link>
      </div>
    </div>
    <div class="loadmore" ref="loadMore">{{ handleLoad }}</div>
    <foot-nav :toTop="scrollTop"></foot-nav>
  </div>
</template>
<script>
import headNav from '../../components/head/index'
import footNav from '../../components/foot/index'
import { throttle, scrollToTop } from '../../utils/utils.js';
import {
  request_articleList,
  request_articleChildrenList } from '../../api/index.js'

export default {
  name: 'articleList',
  components: {
    headNav,
    footNav
  },
  data() {
    return {
      artLists: [],
      listItemNavs: [],
      current_page: 1,
      limit: 6,
      last_page: 1,
      loadStatus: 'load'
    }
  },
  methods: {
    back() {
      this.$router.go(-1);
    },
    scrollTop() {
      scrollToTop();
    },
    reqArtList(artListId) {
      request_articleChildrenList(artListId)
        .then(res => {//根据类别id获取子分类
          this.listItemNavs = res.data.data;
          return res;
        })
        .then(res => {//根据当前子分类id获取文章列表
          this.reqArticle(this.$route.query.listId, this.current_page, this.limit);
        });
    },
    reqArticle(artId, page, limit) {//请求文章列表
      request_articleList(artId, page, limit)
        .then(res => {
          let data = res.data[artId].data[0];

          this.artLists = [...this.artLists, ...data.data];
          this.current_page = Number(data.current_page);
          this.last_page = data.last_page;
          if(Number(data.current_page) >= data.last_page) {
            this.loadStatus = 'noMore';
          } else {
            this.loadStatus = 'load';
          }
        });
    },
    handleListChange(id) {
      this.current_page = 1;
      this.artLists = '';
      this.loadStatus = 'load';
      this.reqArticle(id, this.current_page, this.limit);
    },
    scrollLoadMore() {
      if(this.$route.name != 'articleList') { return false; }
      if(this.loadStatus == 'noMore' || !this.$refs.loadMore) {
        return false;
      }
      if(this.last_page <= this.current_page) {
        this.loadStatus = 'noMore';
        return false;
      }

      let position = this.$refs.loadMore.getBoundingClientRect();
      // console.log(position);
      if(position.bottom <= window.innerHeight - 90) {
        this.loadStatus == 'loading';
        this.reqArticle(this.$route.query.listId, this.current_page + 1, this.limit);
      }
    }
  },
  computed: {
    createListNav() {
      if(this.listItemNavs.length != 0) {
        let newNav = {id: this.listItemNavs[0].p_id, p_id: this.listItemNavs[0].p_id, name: '综合'};
        this.listItemNavs.unshift(newNav)
        return this.listItemNavs;
      } else {
        return this.listItemNavs;
      }
    },
    handleRouteChange() {
      return this.$route.query.listId;
    },
    handleLoad() {
      if(this.loadStatus == 'noMore') {
        return '我是有底线的！';
      }
      if(this.loadStatus == 'loading') {
        return '正在加载中...';
      }
      return '下滑查看跟多';
    }
  },
  created() {
    this.reqArtList(this.$route.query.artListId);
  },
  mounted() {
    let _this = this;
    let dom = document.getElementsByClassName('list')[0];

    window.addEventListener('scroll', throttle(_this.scrollLoadMore,1000, 500, this),false);

  }
}
</script>
<style lang="scss">
.article-list{
  // height: 100%;
  overflow: hidden;
  padding-top: 100px;
  background-size: 750px 1334px;
  // position: relative;
  // background-image: url('../../assets/images/common/news-info-bg.png');
  // background-repeat: no-repeat;
  // background-position: center top;
  // background-attachment: fixed;
  .nav{
    width: 750px;
    height: 88px;
    position: relative;
    // background: url('../../assets/images/common/head-icon.png') no-repeat;
    background-size: 750px 37px;
    background-position: center bottom;
    background-color: #ffffff;
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
  .nav-list{
    width: 549px;
    margin-top: $mgTop;
    padding: 0 10px;
    color: #242437;
    font-size: 24px;
    .item{
      font-size: 30px;
      color: #000000;
    }
    .item-act{
      color: #c09520;
    }
  }
  .list{
    // padding: 0 46px 0 20px;
    min-height: 800px;
    p{
      margin-block-start: 6px;
      margin-block-end: 6px;
    }
    .item{
      padding: 34px 46px 0 20px;
      box-sizing: border-box;
      position: relative;
      width: 750px;
      height: 192px;
    }
    .item:before{
      position: absolute;
      bottom: 0;
      left: 130px;
      content: '';
      width: 60px;
      height: 2px;
      background: #d4b355;
    }
    .time{
      width: 90px;
      height: 158px;
      flex-shrink: 0;
      text-align: right;
      >p:nth-child(1){
        font-size: 24px;
        color: #64646b;
      }
      >p:nth-child(2){
        font-size: 22px;
        color: #707077;
      }
    }
    .txt{
      padding-left: 18px;
      overflow: hidden;
      height: 158px;
      >p:nth-child(1){
        color: #242437;
        font-size: 30px;
        overflow:hidden;
        white-space: nowrap;
        text-overflow:ellipsis;
      }
      >p:nth-child(2){
        margin-top: 20px;
        font-size: 24px;
        color: #707077;
        overflow: hidden;
        display:-webkit-box;
        overflow:hidden;
        text-overflow:ellipsis;
        -webkit-line-clamp:2;
        -webkit-box-orient:vertical;
        line-height: 36px;
      }
    }
  }
  .loadmore{
    margin: 10px auto 180px;
    width: 204px;
    height: 58px;
    line-height: 58px;
    color: #c0951e;
    border: 2px solid #c0951e;
    border-radius: 19px;
    text-align: center;
  }
}
.article-list:before{
  content: ' ';
  position: fixed;
  z-index: 0;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  z-index: -1;
  background:  url(../../assets/images/common/news-info-bg.png) no-repeat center bottom;
  background-size: 100% auto;
}
</style>

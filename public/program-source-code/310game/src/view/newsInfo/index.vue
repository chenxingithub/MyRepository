<template>
  <div id="news-info">
    <head-nav></head-nav>
    <div class="news-box">
      <div class="head-bg"></div>
      <div class="content-box">
        <div class="content">
          <div class="tit-bg"></div>
          <div class="tit clearfix">
            <!-- <ul class="clearfix"> -->
              <router-link v-for="(item, index) in listIdData" :key="index" :to="{path: '/newsInfo', query: {listId: item.id}}"
                :class="[selectedId == item.id ? 'act' : '','tit-item']" @click="handleNavChange(item.id, item.name)">
                {{ item.name }}
              </router-link>
              <!-- <li :class="[selectedId == 1 ? 'act' : '']" @click="handleNavChange(1)">新闻</li>
              <li :class="[selectedId == 2 ? 'act' : '']" @click="handleNavChange(2)">公告</li>
              <li :class="[selectedId == 3 ? 'act' : '']" @click="handleNavChange(3)">活动</li> -->
            <!-- </ul> -->
          </div>
          <div class="list-box">
            <div v-for="(item, index) in list" :key="index">
              <router-link :to="{path: '/article', query: {id: item.id}}" class="list-item" href="javascript:;" v-if="!item.jump_url">
                <div class="time" style='font-family:"宋体","Arial Narrow";'>
                  <div class="month">{{ item.updated_at.split(' ')[0].split('-')[1] }}<span>th</span></div>
                  <div class="day">{{ item.updated_at.split(' ')[0].split('-')[2] }}</div>
                </div>
                <div class="item">
                  <p><span>{{ handleNavName(item.artisan_type_id) }}</span>{{ item.title }}</p>
                  <p>{{ item.subtitled }}</p>
                </div>
              </router-link>
              <a class="list-item" :href="item.jump_url" v-if="item.jump_url" target="_blank">
                <div class="time" style='font-family:"宋体","Arial Narrow";'>
                  <div class="month">{{ item.updated_at.split(' ')[0].split('-')[1] }}<span>th</span></div>
                  <div class="day">{{ item.updated_at.split(' ')[0].split('-')[2] }}</div>
                </div>
                <div class="item">
                  <p><span>攻略</span>{{ item.title }}</p>
                  <p>{{ item.subtitled }}</p>
                </div>
              </a>
            </div>
          </div>
          <el-pagination
            background
            next-text="下一页"
            layout="prev, pager, next, total"
            :page-size="limit"
            :total="total"
            :current-page.sync="current_page"
            @current-change="handlePageChange">
          </el-pagination>
        </div>
      </div>
    </div>
    <foot></foot>
  </div>
</template>
<script>
import headNav from '../../components/head/index.vue'
import foot from '../../components/foot/index'
import {
  request_articleChildrenList,
  request_articleList } from '../../api/index.js'

export default {
  name: 'newsInfo',
  components: {
    headNav,
    foot
  },
  data() {
    return {
      selectedId: 79,
      listId: 79,//新闻公告id, 后台文章类可见
      childrenIds: '',
      listIdData: [],//导航栏
      navName: '综合',
      current_page: 1,
      limit: 7,
      last_page: 0,
      total: 0,
      list: null
    }
  },
  computed: {
    // handleNavName() {
    //   if(this.listIdData.length != 0) {

    //   }
    // }
  },
  methods: {
    handleReqList(id, page, limit) {
      request_articleList(id, page, limit)
        .then((res) => {
          let id = this.selectedId;
          this.last_page = res.data[id].data[0].last_page;
          this.total = res.data[id].data[0].total;
          this.limit = res.data[id].data[0].per_page - 0;
          this.current_page = res.data[id].data[0].current_page - 0;
          this.list = res.data[id].data[0].data;
        });
    },
    handleNavChange(id, navName) {
      if(this.selectedId == id) { return false; }
      let current_page = 1;

      this.selectedId = id;
      this.navName = navName;
      this.current_page = current_page;
      this.handleReqList(id, current_page, this.limit);
    },
    handlePageChange(page) {
      this.handleReqList(this.selectedId, page, this.limit);
    },
    handleNavName(id) {
      if(this.listIdData.length > 0) {

        for(let i=0, len=this.listIdData.length; i <= len; i++) {
          if(this.listIdData[i].id == id) {
            return this.listIdData[i].name;
          }
        }
      }
    }
  },
  created() {
    request_articleChildrenList(this.listId)
      .then((res) => {
        this.listIdData = res.data.data;
        this.listIdData.unshift({id: 79, name: '综合'});

        return res.data.data;
      })
      .then((res) => {
        if(!res) { return false; }

        this.handleReqList(this.selectedId, this.current_page, this.limit);
      });
  },
  mounted() {
    this.selectedId = this.$route.query.listId;
  },
  beforeRouteUpdate (to, from, next) {

    let id = to.query.listId, page = 1;
    this.selectedId = id;
    this.current_page = page;
    this.handleReqList(id, page, this.limit);
    this.handleNavName(id);

    next();
  }
}
</script>
<style lang="scss">
#news-info {
  // padding-top: 80px;
  .news-box{
    min-width: 1200px;
  }
  .head-bg{
    width: 100%;
    height: 700px;
    background: url('../../assets/newsInfo/head-bg.png') no-repeat;
    background-size: 1920px auto;
    background-position: center top;
  }
  $over-margin: 305px;
  .content-box{
    width: 100%;
    min-width: 1200px;
    background: url('../../assets/newsInfo/content-bg.png') no-repeat;
    background-size: 1920px auto;
    background-position: center top;
    margin-top: -$over-margin;
    padding-top: 188px;
    z-index: 1;
  }
  .content{
    min-height: 700px;
    width: 1000px;
    margin: 0 auto;
    min-height: 200px;
    // background: rgba(165, 241, 219, 0.4);
    padding: 38px 0;
  }
  .tit-bg{
    width: 1000px;
    height: 56px;
    background: url('../../assets/newsInfo/head-title.png') no-repeat;
    background-size: 844px 56px;
    background-position: center top;
    margin: 0 auto 27px;
  }
  .tit{
    height: 66px;
    border-bottom: 2px solid #cbd3df;
    box-sizing: border-box;
    text-align: center;
    padding: 9px 0;
    .tit-item{
      list-style: none;
      // float: left;
      display: inline-block;
      width: 136px;
      height: 41px;
      line-height: 41px;
      text-align: center;
      color: #909bb3;
      cursor: pointer;
      user-select: none;
      font-size: 24px;
    }
    .act{
      background: #6797dd;
      color: #ffffff;
    }
  }
  .list-box{
    li{
      list-style: none;
    }
  }
  .list-item{
    $h: 124px;
    display: block;
    width: 100%;
    height: $h;
    margin: 17px 0;
    // background: rgba(132, 188, 240, 0.3);
    // font-family:"宋体","Arial Narrow";
    .time{
      margin: 21px 20px 22px;
      width: 81px;
      height: 81px;
      float: left;
      box-sizing: border-box;
      // background: rgba(162, 134, 238, 0.2);
      background: url('../../assets/newsInfo/item-bar-bg.png') no-repeat;
      background-size: 81px 81px;
      background-position: center;
      font-size: 40px;
      font-family:"宋体","Arial Narrow";
      >div{
        width: 100%;
        height: 40px;
      }
    }
    .month{
      color: #9fa6b4;
      line-height: 26px;
      margin-left: -4px;
      span{
        vertical-align: middle;
        font-size: 14px;
      }
    }
    .day{
      text-align: right;
      color: #000000;
    }
    .item{
      box-sizing: border-box;
      float: right;
      width: 860px;
      height: 80px;
      margin: ($h - 80px)/2 0;
      padding-left: 20px;
      border-left: 1px solid #9fa6b4;
      // background: rgba(0,0,0, .3);
      p{
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
      }
      p:nth-child(1) {
        color: #777281;
        font-size: 24px;
        width: 540px;
      }
      p:nth-child(2) {
        margin-top: 32px;
        color: #81899a;
        font-size: 14px;
      }
      span{
        display: inline-block;
        width: 70px;
        height: 25px;
        line-height: 28px;
        text-align: center;
        background: #9ea9c0;
        font-size: 16px;
        border-radius: 10px;
        color: #ffffff;
        margin-right: 7px;
      }
    }
  }
  .list-item:hover{
    background: #e3ebef;
    transition: background 0.15s linear;
    .item{
      span{
        background: #ff8193;
        transition: background 0.15s linear;
      }
    }
  }
}
.el-pagination{
  text-align: center;
}
.el-pagination{
  margin: 39px 0 0;
}
.el-pagination.is-background .btn-next, .el-pagination.is-background .btn-prev, .el-pagination.is-background .el-pager li{
  background: #ffffff;
}
</style>

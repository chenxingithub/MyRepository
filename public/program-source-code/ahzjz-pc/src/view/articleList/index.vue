<template>
  <div id="articleList">
    <div class="articleList-con">
      <div class="art-list-head"></div>
      <div class="art-return">
        <router-link to="/" class="art-return-btn"></router-link>
      </div>
      <div class="art-list-con">
        <ul class="art-list-tit">
          <router-link :to="{path: '/articleList', query: {listId: articleIds[0]}}"
            tag="li" :class="{'active': this.$route.query.listId == articleIds[0]}">
            综合
          </router-link>
          <router-link :to="{path: '/articleList', query: {listId: articleIds[1]}}"
            tag="li" :class="{'active': this.$route.query.listId == articleIds[1]}">
            公告
          </router-link>
          <router-link :to="{path: '/articleList', query: {listId: articleIds[2]}}"
            tag="li" :class="{'active': this.$route.query.listId == articleIds[2]}">
            攻略
          </router-link>
          <router-link :to="{path: '/articleList', query: {listId: articleIds[3]}}"
            tag="li" :class="{'active': this.$route.query.listId == articleIds[3]}">
            活动
          </router-link>
        </ul>

        <!-- <router-link v-for="(item,index) in articleList" :key="index"
          :to="{path: '/article'}"
          class="art-item border-box"
          tag="div">
          <p class="data">
            <span>18/09</span>
          </p>
          <p class="art-txt">
            公告文案公告文案公告文案公告文案公告文案公告文公告文公告文案案案
          </p>
          <div class="art-link art-link-active"></div>
        </router-link> -->
        <div v-for="(item,index) in articleList" :key="index" class="art-item border-box">
          <router-link v-if="!item.jump_url"
          :to="{path: '/article', query: {articleId: item.id}}">
            <!-- 跳路由 -->
            <p class="data">
              <span>{{ item.updated_at.split(' ')[0].substr(5).split('-').reverse().join('/') }}</span>
            </p>
            <p class="art-txt">
              {{ item.title }}
            </p>
            <div class="art-link art-link-active"></div>
          </router-link>
          <a :href="item.jump_url" v-if="item.jump_url" target="_blank">
            <!-- 跳外链 -->
            <p class="data">
              <span>{{ item.updated_at.split(' ')[0].substr(5).replace(/-/g, '/') }}</span>
            </p>
            <p class="art-txt">
              {{ item.title }}
            </p>
            <div class="art-link art-link-active"></div>
          </a>
        </div>

      </div>
      <div class="pagination-box">
         <el-pagination
          :page-size=limit
          :page-count=totalPage
          :pager-count=5
          layout="prev, pager, next"
          :total=total
          @current-change="handleCurrentChange">
        </el-pagination>
      </div>
    </div>
    <foot></foot>
  </div>
</template>
<script>
import { request_articleList } from '../../api/index.js'
import foot from '../../components/foot/index'

export default {
  name: 'articleList',
  components: {
    foot
  },
  data() {
    return {
      articleIds: [73,51,49,50], //综合，公告，攻略，活动
      articleList: null,
      cur_page: 1,
      limit: 12,
      total: 1,
      totalPage: 1,
      activeId: this.$route.query.listId,
    }
  },
  methods: {
    request_list(id) {//根据id请求列表
      let _this = this;
      request_articleList(id, this.cur_page, this.limit).then(res => {
        _this.articleList = res.data[id].data[0].data;
        _this.cur_page = Number(res.data[id].data[0].current_page);
        _this.total = Number(res.data[id].data[0].total);
        _this.totalPage = Number(res.data[id].data[0].last_page);
      });
    },
    handleCurrentChange(val) {

      this.cur_page = val;
      this.request_list(this.$route.query.listId);
    }
  },
  created() {
    this.request_list(this.$route.query.listId);
  },
  beforeRouteUpdate(to, from, next) {//导航栏切换初始化
    this.activeId = to.query.listId;
    this.cur_page = 1;
    this.request_list(to.query.listId);//切换后请求
    next();
  }
}
</script>
<style lang="scss">
#articleList{
  min-width: 1200px;
  height: 100%;
  position: relative;
  @include bg('../../assets/images/article-list-bg.png', 1920px, 1362px, center, top);
  // background-attachment: fixed;

  .articleList-con{
    width: 1200px;
    min-height: 1076px;
    margin: 0 auto;
  }
  .art-list-head{
    width: 1200px;
    height: 160px;
    margin: 0 auto;
    @include bg('../../assets/images/news-tit.png',1175px,65px,center,bottom);
  }
  .art-return{
    width: 100%;
    height: 92px;
    position: relative;
    cursor: pointer;
    .art-return-btn{
      width: 241px;
      height: 92px;
      position: absolute;
      top: 0;
      right: 0;
      @include bg('../../assets/images/toHome-btn.png');
    }
  }
  .art-list-con{
    width: 1164px;
    height: 950px;
    padding: 0 18px;
    // background: rgba(92, 243, 235, 0.5);
    .art-list-tit{
      height: 40px;
      margin-bottom: 17px;
      border-bottom: 2px solid #7b7b7b;
      li{
        list-style: none;
        float: left;
        width: 67px;
        margin-right: 20px;
        font-size: 28px;
        color: #aeaeae;
        text-align: center;
        cursor: pointer;
      }
      li:first-child{
        text-align: left;
      }
      .active{
        color: #970e0f;
      }
    }
  }
  .art-item{
    width: 275px;
    height: 255px;
    box-sizing: border-box;
    box-shadow: inset 0 0 0 1px #383737;
    padding: 28px 0 0 10px;
    position: relative;
    float: left;
    margin:0 20px 32px 0;
    cursor: pointer;
    .data{
      height: 38px;
      width: 212px;
      color: #3d3f3f;
      font-size: 28px;
      border-bottom: 2px solid #292b2b;
    }
    .art-txt{
      width: 195px;
      padding: 25px 0 0 0;
      box-sizing: border-box;
      color: #cecece;
      font-size: 18px;
      text-align: justify;
      line-height: 22px;
    }
    .art-link{
      width: 40px;
      height: 38px;
      border: 2px solid #676767;
      position: absolute;
      right: 15px;
      bottom: 16px;
      @include bg('../../assets/images/active-arrow-right.png', 8px, 17px, center, center);
    }
    .art-link-active{
      @include bg('../../assets/images/fea-right-arrow.png', 8px, 17px, center, center);
    }
  }
  .art-item:nth-child(4n+1){
    margin-right: 0;
  }
  .art-item:hover{
    background: #000000;
    box-shadow: inset 0 0 0 1px #960e0f;
    transition: all 0.3s linear;
    .data{
      color: #ffffff;
      border-bottom-color: #ffffff;
      transition: all 0.3s linear;
    }
    .art-link{
      border: 2px solid #960e0f;
      @include bg('../../assets/images/active-arrow-right.png', 8px, 17px, center, center);
      transition: all 0.3s linear;
    }
  }
  .pagination-box{
    height: 100px;
  }
}
.el-pagination{// 修改element-ui默认样式
  text-align: right;
  color: #605f5e;
}
.el-pagination li{
  background: none;
}
.el-pager li.active{
  color: #c8c8c8;
}
.el-pager li:hover{
  color: #c8c8c8;
}
.el-pagination button:hover{
  color: #c8c8c8;
}
.el-pagination .btn-next, .el-pagination .btn-prev{
  color: #605f5e;
  background: center center no-repeat none;
}
.el-pagination button{
  border: 1px solid #494444;
}
.el-pagination button:disabled{
  background-color: transparent;
}
</style>

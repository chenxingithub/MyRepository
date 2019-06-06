<template>
  <div id="article-dec">
    <div class="article-content">
      <div class="art-head clearfix">
        <div class="art-download-box clearfix" v-if="baseInfo">
          <div class="art-code" :style="{backgroundImage: 'url('+ imgDomain +'/'+ baseInfo.android_dow_code_img +')'}"></div>
          <div class="art-download-btn">
            <a :href="iosDownloadUrl" target="_blank"></a>
            <a :href="androidDownloadUrl" target="_blank"></a>
          </div>
        </div>
        <router-link to="/" tag="div" class="art-return-box"></router-link>
      </div>
      <div class="art-dec border-box">
        <div class="art-title border-box" v-if="article">
          <div>
            <p>{{ article.title }}</p>
            <span>{{article.updated_at.split(' ')[0].replace(/-/g,'.')}}</span>
          </div>
        </div>
        <div class="art-text border-box" v-if="article" v-html="article.content">
          <!-- {{ article.content }} -->
        </div>
      </div>
    </div>
    <foot></foot>
  </div>
</template>
<script>
import { request_article } from '../../api/index.js'
import foot from '../../components/foot/index'
import { mapState, mapGetters } from 'vuex'

export default {
  name: 'articleDec',
  components: {
    foot
  },
  computed: {
    ...mapState([
      'baseInfo',
      'imgDomain'
      ]),
    ...mapGetters([
      'iosDownloadUrl',
      'androidDownloadUrl'
    ])
  },
  data() {
    return {
      article: null
    }
  },
  methods: {
    isLink(link) {
      let regex = /^http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- ./?%&=]*)?$/;

      return regex.test(link);
    }
  },
  created() {
    request_article(this.$route.query.articleId).then(res => {
      // console.log(res);
      this.article = res.data.data;
    });
  }
}
</script>
<style lang="scss">
#article-dec{
  min-width: 1300px;
  max-width: 1920px;
  height: 100%;
  position: relative;
  @include bg('../../assets/images/article-bg.png', 1920px, 1080px, center, top);
  background-color: #070505;
  .article-content{
    width: 1300px;
    min-height: 1080px;
    margin: 0 auto;
    .art-head{
      width: 1200px;
      height: 104px;
      margin: 0 auto;
      position: relative;
      top: 212px;
    }
    .art-download-box{
      width: 260px;
      height: 103px;
      float: left;
      .art-code{
        width: 104px;
        height: 103px;
        box-shadow: inset 0 0 0 2px #7b7b7b;
        float: left;
        // @include bg('../../assets/images/download-code-icon.png', 90px, 89px, center, center);
        background-size: 90px 89px;
        background-repeat: no-repeat;
        background-position: center center;
      }
      .art-download-btn{
        float: right;
        >a{
          display: block;
          width: 146px;
          height: 42px;
          box-shadow: inset 0 0 0 2px #9f8b64;
          cursor: pointer;
          background-color: #141414;
        }
        >a:hover{
          background-color: #2e0306;
        }
        a:nth-child(1){
          margin-top: 2px;
          @include bg('../../assets/images/ios-download-btn.png', 115px, auto, center, center);
        }
        a:nth-child(2){
          margin-top: 15px;
          @include bg('../../assets/images/android-download-btn.png', 115px, auto, center, center);
        }
      }
    }
    .art-return-box{
      width: 241px;
      height: 92px;
      float: right;
      cursor: pointer;
      @include bg('../../assets/images/toHome-btn.png');

    }
    .art-dec{
      width: 1300px;
      min-width: 1200px;
      min-height: 800px;
      margin-top: 240px;
      // background: rgba(115, 246, 255, 0.5);
      color: #ffffff;
      .art-title{
        width: 100%;
        height: 212px;
        @include bg('../../assets/images/article-dec-head-bg.png',1300px,245px, center,top);
        text-align: center;
        padding: 60px 50px 0 50px;
        >div{
          width: 1042px;
          height: 100%;
          margin: 0 auto;
          box-sizing: border-box;
          border-bottom: 1px solid #4c3e34;
        }
        p{
          font-size: 36px;
          color: #7b6352;
          font-weight: 600;
          letter-spacing: 10px;
           margin-bottom: 26px;
        }
        span{
          font-size: 16px;
          color: #929292;
        }
      }
      .art-text{
        width: 1300px;
        padding: 48px 133px 90px 138px;
        @include bg('../../assets/images/article-dec-con-bg.png');
        background-repeat: repeat-y;
        text-align: justify;
        min-height: 588px
      }
    }
  }
}
</style>

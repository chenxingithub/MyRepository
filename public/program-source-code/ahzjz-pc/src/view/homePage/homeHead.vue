<template>
  <div class="homeHead">
    <div class="home-head-title">
      <div class="home-tit-logo"></div>
    </div>
    <div>
      <video src="../../assets/video/home-head-video.mp4" class="bg-video" muted="" preload="" autoplay="autoplay" loop="loop"></video>
    </div>
    <div class="head-box border-box">
      <div class="video-box" @click="$emit('toggleVideo')">
        <div class="video-cover"></div>
        <div class="video-btn"></div>
      </div>
      <div class="download-box" v-if="baseInfo">

        <a :href="iosDownloadUrl"
          target="_blank" class="ios-download"></a>
        <a :href="androidDownloadUrl"
          target="_blank" class="android-download"></a>
      </div>
      <div class="wxcode-box">
        <div class="download-icon" v-if="baseInfo"
          :style="{backgroundImage: 'url('+ imgDomain +'/'+ baseInfo.android_dow_code_img +')'}">
        </div>
        <div class="scan-bar"></div>
      </div>
      <div class="download-tip">扫一扫下载</div>
    </div>
    <div class="arrow-next" @click="$emit('slideNext')"></div>
  </div>
</template>
<script>
import { mapState, mapGetters } from 'vuex'

export default {
  name: 'HomeHead',
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
  methods: {
    isLink(link) {
      let regex = /^http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- ./?%&=]*)?$/;

      return regex.test(link);
    }
  }
}
</script>
<style lang="scss">
.homeHead{
  min-width: 1200px;
  height: 1077px;
  position: relative;
  overflow: hidden;
  // z-index: -1;
  background: url('../../assets/images/home-head-bg.png') no-repeat;
  background-size: 1920px 1077px;
  background-position: top center;
  .arrow-next{//下一页箭头
    width: 67px;
    height: 45px;
    position: absolute;
    bottom: 140px;
    left: 50%;
    margin-left: -34px;
    z-index: 100;
    cursor: pointer;
    @include bg('../../assets/images/arrow-down.png');
  }
  .home-head-title{
    width: 790px;
    height: 271px;
    @include bg('../../assets/images/home-slogan.png');
    z-index: 100;
    position: absolute;
    top: 242px;
    left: 50%;
    margin-left: -424px;
  }
  .home-tit-logo{
    width: 376px;
    height: 200px;
    position: absolute;
    top: -185px;
    left: -260px;
    transform: translateX(-50%);
    z-index: 20;
    background: url('../../assets/images/ah-logo.png') no-repeat;
    background-size: 100% auto;
  }
  video{
    position: absolute;
    top: 0;
    left: 50%;
    margin-left: -960px;
    height: 100%;
  }
  .head-box{
    width: 633px;
    height: 190px;
    position: absolute;
    top: 576px;
    left: 50%;
    margin-left: -(633px/2);
    padding: 19px 20px 14px;
    >div {
      float: left;
      width: 149px;
      height: 149px;
    }
    .video-box, .wxcode-box{
      border: 1px solid #fddc9a;
    }

    .download-box{// 首页ios android 下载按钮
      width: 236px;
      height: 149px;
      margin: 0 12px;
      >a{
        width: 236px;
        height: 67px;
        display: block;
        border: 1px solid #fddc9a;
      }
      >a:hover{
        background-color: #2e0306;
        transition: background 0.3s linear;
      }
      .ios-download{
        cursor: pointer;
        @include bg('../../assets/images/ios-download-btn.png', 183px, 40px, center, center);
        background-color: #141414;
      }
      .android-download{
        cursor: pointer;
        margin-top: 13px;
        @include bg('../../assets/images/android-download-btn.png', 180px, 40px, center, center);
      }
    }
    .video-box{
      width: 149px;
      height: 149px;
      cursor: pointer;
      position: relative;
      @include bg('../../assets/images/head-video-bg.gif');
      .video-btn{
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        z-index: 5;
        @include bg('../../assets/images/head-video-btn.png', 95px, 98px, 33px, center);
      }
      .video-cover{
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        background: rgba(0, 0, 0, .4);
      }
    }
    .wxcode-box{
      // float: right;
      @include bg('../../assets/images/download-code-bg.png');
      //download-code-icon.png
      position: relative;
      .download-icon{
        width: 142px;
        height: 142px;
        margin: 3.5px 0 0 3.5px;
        // @include bg('../../assets/images/download-code-icon.png');
        background-size: 100% 100%;
        background-repeat: no-repeat;
      }
      .scan-bar{
        width: 149px;
        height: 5px;
        position: absolute;
        top: 0;
        left: 0;
        animation: slide 2s linear alternate infinite;
        @include bg('../../assets/images/scan-light-bar.png');
      }
    }
    .download-tip{
      width: 38px;
      height: 149px;
      float: left;
      color: #ffffff;
      font-size: 22px;
      position: absolute;
      top: 24px;
      right: 0;
      text-align: center;
      letter-spacing: 1em;
      line-height: 27px;
    }
    @include bg('../../assets/images/head-box-bg.png');
  }
}
@keyframes slide {
  0% {
    top: 4px;
  }
  100%{
    top: 142px;
  }
}
</style>

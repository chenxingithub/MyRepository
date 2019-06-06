<template>
  <div id="main" class="flex">
    <div class="nav">
      <left-nav></left-nav>
    </div>
    <div class="content-box">
      <div class="closeWebview" @click="closeWebview"></div>
      <question></question>
      <div class="content-box-bar"></div>
      <div class="m-content">
        <router-view></router-view>
      </div>
    </div>
  </div>
</template>

<script>
  import leftNav from './head/index'
  import question from './question/index'
  import {
    judgeClientOs
  } from '../utils/utils.js'

  export default {
    name: 'home',
    components: {
      leftNav,
      question
    },
    methods: {
      closeWebview() {
        let os = judgeClientOs();
        if (os == 'iOS') {
          window.webkit.messageHandlers.iOS.postMessage({
            functionName: 'dismiss'
          });
        }
        if (os == 'Android') {
          yhGameSpirit.closeWebView();
        }
      }
    }
  }

</script>

<style lang="scss">
  #main {
    $w: 1140px;
    $h: 723px;
    width: $w;
    height: $h;
    position: absolute;
    top: 50%;
    left: 50%;
    margin: (-$h/2) 0 0 (-$w/2);
    background: url('../assets/common/bg.png') no-repeat;
    background-size: 100% 100%;

    .nav {
      width: 300px;
      height: 645px;
      flex-shrink: 0;
      margin: 34px 0 0 53px;
    }

    .content-box {
      width: 730px;
      height: 636px;
      margin: 34px 0 0 0;
      flex-shrink: 0;
      padding: 0 24px 0 15px;
      position: relative;
    }

    .closeWebview {
      width: 55px;
      height: 55px;
      // background: rgba(101, 185, 211, 0.65);
      position: absolute;
      top: -38px;
      right: -18px;
    }

    .content-box-bar {
      width: 695px;
      height: 13px;
      background: url('../assets/common/content-box-bar.png') no-repeat;
      background-size: 100% 100%;
      margin: 10px 0 14px;
    }

    .m-content {
      width: 699px;
      height: 483px;
      padding: 3px 0;
      background: url('../assets/common/content-box.png') no-repeat;
      background-size: 100% 100%;
      // margin: 0 auto;
    }
  }

</style>

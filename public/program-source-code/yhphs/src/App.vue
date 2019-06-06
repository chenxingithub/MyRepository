<template>
  <div id="app">
    <head-nav></head-nav>
    <keep-alive v-if="$route.meta.keepAlive">
      <router-view/>
    </keep-alive>
    <router-view v-if="!$route.meta.keepAlive"></router-view>
  </div>
</template>

<script>
import './utils/prefetch.js'
import headNav from './components/head/index'
import { request_base_info } from './api/index.js'
import { mapMutations } from 'vuex'
import './plugins/animate.css'
import { judgeClientOs } from './utils/utils.js'

export default {
  name: 'App',
  components: {
    headNav
  },
  methods: {
    ...mapMutations(['saveBaseInfo','saveOs'])
  },
  created() {
    request_base_info()
      .then(res => {
        this.saveBaseInfo(res.data.data[0]);
        this.saveOs(judgeClientOs());
      });
  }
}
</script>

<style lang="scss">
@import './style/base.scss';

#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  width: 750px;
  height: 100%;
  margin: 0 auto;
  position: relative;
}
#loadingPage{
  position: fixed;
  width: 750px;
  height: 100%;
  left: 50%;
  margin-left: -5rem;
  background: url('./assets/images/common/load-bg.jpg') no-repeat;
  background-size: 750px 1334px;
  background-position: center bottom;
  background-color: #ffffff;
  z-index: 9999;
  .load-box{
    width: 329px;
    height: 329px;
    margin: 0 auto;
    position: absolute;
    top: 40%;
    left: 50%;
    margin: -164px 0 0 -164px;
    background: url('./assets/images/common/ouset.png') no-repeat;
    background-size: 100% 100%;
    transform-origin:50% 50%;
    animation: loadRotate 4s linear infinite;
  }
  .load-in{
    width: 134px;
    height: 134px;
    margin: 98px auto;
    background: url('./assets/images/common/inset.png') no-repeat;
    background-size: 100% 100%;
    transform-origin:50% 50%;
    animation: loadRotate 1.4s linear infinite reverse;
  }
  .cunt-box{
    position: absolute;
    left: 50%;
    top: 55%;
    margin-left: -110px;
    min-width: 220px;
    height: 55px;
  }
  .load-num{
    font-size: 56px;
    text-align: center;
  }
  .load-flag{
    margin-left: 10px;
    p{
      margin-block-start: 0;
      margin-block-end: 0;
    }
    span{
      font-size: 30px;
    }
  }
  .dot-ani1{
    animation: loadDot 1.2s linear infinite;
    // animation-delay: 0.5s;
  }
  .dot-ani2{
    animation: loadDot 1.2s linear infinite;
    animation-delay: 0.2s;
  }
  .dot-ani3{
    animation: loadDot 1.2s linear infinite;
    animation-delay: 0.4s;
  }
  @keyframes loadRotate {
    from {
      transform: rotate(0deg);
    }
    to {
      transform: rotate(360deg);
    }
  }
  @keyframes loadDot {
    from {
      opacity: 0;
    }
    to {
      opacity: 1;
    }
  }
}
</style>

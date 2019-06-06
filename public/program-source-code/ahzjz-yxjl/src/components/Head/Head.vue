<template>
  <div id="head">
    <div class="head-tittle"></div>
    <div class="head-nav flex-sa-c">
      <router-link :to="{path: 'hotspot'}" tag="div"
       :class="{'head-selected': selected == 'hotspot' ? true : false}"
       ><div class="head-hotspot"></div></router-link>
      <router-link :to="{path: 'recommend'}" tag="div"
       :class="{'head-selected': selected == 'recommend' ? true : false}"
       ><div class="head-recommend"></div></router-link>
      <router-link :to="{path: 'activity'}" tag="div"
       :class="{'head-selected': selected == 'activity' ? true : false}"
       ><div class="head-activity"></div></router-link>
      <router-link :to="{path: 'feedback'}" tag="div"
       :class="{'head-selected': selected == 'feedback' ? true : false}"
       ><div class="head-feedback"></div></router-link>
    </div>
    <div class="head-border-bar"></div>
    <a href="javascript:;" class="head-close" @click="closeWebview"></a>
  </div>
</template>

<script>
import { judgeClientOs } from '../../utils/utils.js'

export default {
  name: 'headNav',
  data () {
    return {
      selected: 'hotspot'
    }
  },
  methods: {
    exchangeNav() {

      switch(this.$route.name) {
        case 'hotspot': 
          this.selected = 'hotspot';
          break;
        case 'recommend':
          this.selected = 'recommend';
          break;
        case 'activity':
          this.selected = 'activity';
          break;
        case 'feedback':
          this.selected = 'feedback';
          break;
        // default :
        //   this.selected = 'hotspot';
      }
    },
    closeWebview() {
      let os = judgeClientOs();
      if(os == 'iOS') {
            window.webkit.messageHandlers.iOS.postMessage({functionName:'dismiss'});
        }
      if(os == 'Android') {
          yhGameSpirit.closeWebView();
      }
    }
  },
  mounted() {
    this.exchangeNav();
  },
  watch: {
    "$route": "exchangeNav"
  }

}
</script>

<style lang="scss">
#head{
  // padding: 38px 22px 0;
  margin: 0 auto;
  width: 825px;
  position: relative;
  .head-tittle{
    // background: url('../../assets/index-tittle.png') no-repeat;
    // background-size: 805px 139px;
    width: 825px;
    height: 139px;
    margin: 0 auto;
  }
  .head-nav{
    margin: 30px 0 30px;
    @mixin navFont{
        width: 207px;
        height: 98px;
        background: url('../../assets/hotspot-nav-font.png') no-repeat;
        background-size: 825px 98px;
    }
    >div{
      width: 207px;
      height: 98px;
      // background: url('../../assets/head-nav-btn.png') no-repeat;
      // background-size: 855px 209px;
      background: url('../../assets/hotspot-select-btn.png') no-repeat;
      background-size: 207px 98px;
      overflow: hidden;
      flex-shrink: 0;
    }
    .head-hotspot{
      // background-position: -3px -113px;
      @include navFont;
      background-position: 0 0;
    }
    .head-recommend{
      @include navFont;
      background-position: -207px 0;
    }
    .head-activity{
      @include navFont;
      background-position: -414px 0;
    }
    .head-feedback{
      @include navFont;
      background-position: -621px 0;
    }
    .head-selected{
      background: url('../../assets/hotspot-selected-btn.png') no-repeat;
      background-size: 100% 100%;
    }
  }
  .head-border-bar{
    width: 810px;
    height: 3px;
    margin: 0 auto;
    background: url('../../assets/head-boder-bar.png') no-repeat;
    background-size: 100% 100%;
  }
  .head-close{
    width: 104px;
    height: 140px;
    display: block;
    position: absolute;
    top: 16px;
    right: -50px;
    background: url('../../assets/recommend-modal-close.png') no-repeat;
    background-size: 100% 100%;
  }
}
</style>

<template>
  <div id="head">
    <div class="head-tittle"></div>
    <div class="head-nav flex-sa-c">
      <router-link :to="{path: 'hotspot'}" tag="div"
       :class="{'head-hotspot': true, 'head-selected': selected == 'hotspot' ? true : false}"
       ></router-link>
      <router-link :to="{path: 'recommend'}" tag="div"
       :class="{'head-recommend': true, 'head-selected': selected == 'recommend' ? true : false}"
       ></router-link>
      <router-link :to="{path: 'activity'}" tag="div"
       :class="{'head-activity': true, 'head-selected': selected == 'activity' ? true : false}"
       ></router-link>
      <router-link :to="{path: 'feedback'}" tag="div"
       :class="{'head-feedback': true, 'head-selected': selected == 'feedback' ? true : false}"
       ></router-link>
    </div>
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
          android.closeWebView();
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
  padding: 38px 22px 0;
  width: 10rem;
  position: relative;
  .head-tittle{
    background: url('../../assets/index-tittle.png') no-repeat;
    background-size: 805px 139px;
    width: 805px;
    height: 139px;
    margin: 0 auto;
  }
  .head-nav{
    margin: 50px 0 40px;
    >div{
      width: 200px;
      height: 100px;
      background: url('../../assets/head-nav-btn.png') no-repeat;
      background-size: 899px 244px;
    }
    .head-hotspot{
      background-position: -18px -125px;
    }
    .head-recommend{
      background-position: -238px -125px;
    }
    .head-activity{
      background-position: -459px -125px;
    }
    .head-feedback{
      background-position: -680px -125px;
    }
    .head-selected{
      background-position-y: -12px;
    }
  }
  .head-close{
    width: 96px;
    height: 96px;
    display: block;
    position: absolute;
    top: 0;
    right: 0;
    background: url('../../assets/recommend-modal-close.png') no-repeat;
    background-size: 100% 100%;
  }
}
</style>

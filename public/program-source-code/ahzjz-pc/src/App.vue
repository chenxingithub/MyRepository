<template>
  <div id="app">
    <router-view/>
  </div>
</template>

<script>
import { mapState, mapMutations, mapActions } from 'vuex'
import { request_base_info } from './api/index.js'
import { isPC } from './utils/utils.js'

export default {
  name: 'App',
  computed:{
    ...mapState(['mobile_url'])
  },
  methods: {
    ...mapMutations([
      'saveBaseInfo',
    ]),
    ...mapActions([
      'tipModalAct'
    ]),
    adaptation2Mobile() {
      if(isPC()) { return false; }

      let url = '';
      switch(this.$route.path) {
        case "/articleList":
          url = this.mobile_url + 'listNav?type=gameStrategy&id=49';
          break;
        case "/article":
          url = this.mobile_url + 'articledetails?id='+this.$route.query.articleId;
          break;
        case "/giftBag":
          url = this.mobile_url + 'giftbag/giftAll';
          break;
        default:
          url = this.mobile_url + 'index';
      }

      window.location.href = url;
    }
  },
  created() {
    this.adaptation2Mobile();

    request_base_info().then(res => {//获取基本配置
      this.saveBaseInfo(res.data.data[0]);
    }).catch(error => {
      this.tipModalAct(error.response.data.message)
    });
  },
  mounted() {
    window.onresize = () => {
      this.adaptation2Mobile();
    }
  }
}
</script>

<style lang="scss">
@import './style/base.scss';
html,body {
  background: #0a0a0a;
}
#app {
  /* font-family: 'Avenir', Helvetica, Arial, sans-serif; */
  font-family: 'Microsoft Yahei';
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
</style>

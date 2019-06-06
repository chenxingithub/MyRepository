<template>
  <div id="app">
    <log-reg></log-reg>
    <!-- <head-nav></head-nav> -->
    <router-view/>
    <!-- <foot></foot> -->
    <tip-modal></tip-modal>
  </div>
</template>

<script>
import { mapMutations, mapActions } from 'vuex'
import animated from 'animate.css'
import logReg from './components/logRegModal/index.vue'
import headNav from './components/head/index.vue'
import foot from './components/foot/index'
import { request_base_info, req_userInfo } from './api/index.js'
import tipModal from './components/tipMessage'

export default {
    name: 'App',
    components: {
        headNav,
        foot,
        logReg,
        tipModal
    },
    methods: {
      ...mapMutations([
        'saveUserInfo',
        'saveAuthInfo'
      ]),
      ...mapActions([
        'tipModalAct',
        'handleLogout'
      ])
    },
    created() {
      let user = this.storejs.get('user');
      if(user) {//本地有存储用户名及token则请求获取用户信息
      // console.log(user);
        this.saveAuthInfo(user);
        req_userInfo(user.user_name, user.access_token)
          .then(res => {
            let data = res.data;
            if(data.ret == 1) {
              this.saveUserInfo(data.content);
            } else {
              // console.log(data.msg);
              this.handleLogout();

              this.tipModalAct({msg: data.msg, type: 'fail'});
            }
            // console.log(res);
          })
      }
      request_base_info().then(resolve => {
          // console.log(resolve);
      });
    }
}
</script>

<style lang="scss">
@import './style/base.scss';
#app {
    /* font-family: 'Avenir', Helvetica, Arial, sans-serif; */
    // font-family: 'Microsoft Yahei';
    // -webkit-font-smoothing: antialiased;
    // -moz-osx-font-smoothing: grayscale;
    position: relative;
    width: 100%;
    min-width: 1200px;
    margin: 0 auto;
}
</style>

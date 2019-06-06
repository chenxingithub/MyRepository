<template>
  <div id="user-center">
    <head-nav></head-nav>
    <div class="user-nav-tit">
      当前位置：<router-link to='/' tag="span">冰鸟首页</router-link>>
      <router-link tag="span" to="/userCenter/uAdmin">个人中心</router-link>
    </div>
    <div class="user-box clearfix">
      <div class="nav bor-box">
        <user-nav></user-nav>
      </div>
      <div class="content bor-box">
        <router-view></router-view>
      </div>
    </div>
    <foot class="clearfix"></foot>
  </div>
</template>
<script>
import headNav from '../../components/head/index.vue'
import foot from '../../components/foot/index'
import { mapState } from 'vuex'
import userNav from './userNav'

export default {
  name: 'userCenter',
  components: {
    userNav,
    headNav,
    foot
  },
  computed: {
    ...mapState([
      'AuthInfo'
    ])
  },
  methods: {
    logout() {
       // let user = this.storejs.get('user');
      let user = this.$store.state.AuthInfo.access_token;
      // console.log(this.$store.state.AuthInfo);
      if(!user) {
        this.$router.push({path: '/'});
      }
      // console.log(this)
    }
  },
  created() {
    this.logout();
  },
  // updated() {
  //   this.logout();
  // },
  watch: {
    'AuthInfo' : 'logout',
    deep: true
  }
}
</script>
<style lang="scss">
#user-center{
  $border-r: 5px;
  min-height: 550px;
  width: 100%;
  margin: 0 auto;
  position: relative;
  top: 80px;
  // box-sizing: border-box;
  // border: 1px solid #ccd8e4;
  // background: #ffffff;
  border-radius: $border-r;
  .user-nav-tit{
    $h: 50px;
    width: 1200px;
    height: $h;
    line-height: $h + 10px;
    margin: 0 auto;
    color: #656565;
    font-size: 14px;
    >span{
      cursor: pointer;
    }
  }
  .nav{
    width: 278px;
    min-height: 548px;
    float: left;
    padding: 0 6px 0 4px;
    border-right: 2px solid #31aae2;
    border-radius: $border-r 0 0 $border-r;
  }
  .content{
    width: 920px;
    height: 100%;
    min-height: 548px;
    float: right;
    padding: 27px 34px 0 48px;
    border-radius: 0 $border-r  $border-r 0;
  }
  .user-box{
    min-height: 548px;
    width: 1200px;
    margin: 0 auto;
    box-sizing: border-box;
    border: 1px solid #ccd8e4;
    background: #ffffff;
    margin-bottom: 56px;
  }
}
</style>

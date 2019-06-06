<template>
  <div class="user-admin">
    <p class="tit">我的主页 <span></span></p>
    <div class="head-portrait">
      <div class="icon"><img src="../../../assets/user_icon.png" alt="user"></div>
      <p>账号：<span>{{ AuthInfo.user_name }}</span></p>
    </div>
    <div class="safety-index" v-if="userInfo">
      <p>
        安全指数：<label :style="{color: safetyIndex[0]}">{{safetyIndex[3]}}</label>
        <span  :style="{'background': safetyIndex[0]}"></span>
        <span  :style="{'background': safetyIndex[1]}"></span>
        <span  :style="{'background': safetyIndex[2]}"></span>
      </p>
      <p v-if="!userInfo.tel_num || !userInfo.real_id">
        <img src="../../../assets/safety-m.png" alt="safety">
        <span>为防止账号被盗，建议您开启以下全部安全设置</span>
      </p>
    </div>
    <div class="auth-box clearfix">
      <div class="bind-tel-list clearfix">
        <img class="auth-icon" src="../../../assets/bindtel-icon.png" alt="bind-tel-icon">
        <div class="auth-status">
          <p>绑定手机 <span v-if="userInfo">{{ userInfo.tel_num ? '已设置' : '未设置' }}</span></p>
          <p>用于密码修改时短信提示和密码遗失时重置密码</p>
        </div>
        <router-link to="bindTel" :class="['btn', !userInfo.tel_num ? 'btn-hover' : 'is-set']"
        v-if="userInfo">{{ userInfo.tel_num ? '绑定手机' : '绑定手机' }}</router-link>
      </div>
      <div class="name-auth-list clearfix">
        <img class="auth-icon" src="../../../assets/nameauth.png" alt="nameauth">
        <div class="auth-status">
          <p>实名认证 <span v-if="userInfo">{{ userInfo.real_name ? '已设置' : '未设置' }}</span></p>
          <p>用于账号防沉迷认证和密码遗失时重置密码</p>
        </div>
        <router-link to="nameAuth" :class="['btn', !userInfo.real_name ? 'btn-hover' : 'is-set']" v-if="userInfo">{{ userInfo.real_name ? '认证' : '认证' }}</router-link>
      </div>
    </div>
  </div>
</template>
<script>
import { mapState } from 'vuex'

export default {
  name: 'userAdmin',
  data() {
    return {
    }
  },
  computed: {
    ...mapState([
      'userInfo',
      'AuthInfo'
    ]),
    safetyIndex() {
      let data = [
        '#ef4747',
        '#d3d3d3',
        '#d3d3d3',
        '低'
      ];
      if(this.userInfo) {
        // tel_num real_id
        // console.log(this.userInfo);
        if(!this.userInfo.real_id && !this.userInfo.tel_num) {
          return data;
        }
        if(!this.userInfo.real_id || !this.userInfo.tel_num) {
          data = ['#f1c50e','#f1c50e','#d3d3d3','中'];
        }
        if(this.userInfo.real_id && this.userInfo.tel_num) {
          data = ['#90cc42','#90cc42','#90cc42','高'];
        }
      }

      return data;
    }
  }
}
</script>
<style lang="scss">
@import './style.scss';
.user-admin{
  @include tit;
  font-size: 18px;
  .tit{
    span{
      position: absolute;
      top: 0;
      right: 0;
      color: #656565;
      font-size: 14px;
      cursor: pointer;
      text-decoration: underline;
    }
  }
  .head-portrait{
    height: 179px;
    border-bottom: 1px solid #ebebeb;
    width: 100%;
    .icon{
      width: 124px;
      height: 124px;
      float: left;
      margin: 30px 0 0;
      border-radius: 50%;
      // background: #a8a8a8;
      img{
        width: 100%;
      }
    }
    p{
      float: left;
      margin: 83px 31px 0;
      font-size: 18px;
      color: #656565;
    }
  }
  .safety-index{
    text-align: center;
    p:nth-child(1){
      margin: 28px 0 18px;
      span{
        display: inline-block;
        width: 65px;
        height: 10px;
        margin: 0 5px;
        background: #d3d3d3;
      }
      .active{
        background: #f1c50e;
      }
    }
    p:nth-child(2) {
      img{
        vertical-align: text-bottom;
      }
    }
  }
  .auth-box{
    .auth-icon{
      float: left;
      margin: 0 22px;
    }
    .auth-status{
      float: left;
      p:nth-child(1){
        font-size: 18px;
        color: #727272;
        span{
          color: $account-color;
        }
      }
      p:nth-child(2){
        margin-top: 5px;
        font-size: 14px;
        color: #656565;
      }
    }
    .btn{
      width: 137px;
      height: 46px;
      line-height: 46px;
      text-align: center;
      float: right;
      color: #ffffff;
      background: $account-color;
      border-radius: 5px;
      cursor: pointer;
    }
    .btn-hover:hover{
      background: #f4b122;
    }
    .is-set{
      background: #b3b9bc;
    }
    .bind-tel-list{
      margin: 48px 0 38px;
    }
    .name-auth-list{
      .auth-icon{
        margin-right: 12px;
      }
    }
  }
}
</style>

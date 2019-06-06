<template>
    <div class="head-nav">
        <div class="head-box">
            <router-link  tag="div" class="logo" to="/"></router-link>
            <div class="login-reg" >
                <div v-if="!AuthInfo.access_token">
                  <button class="login" @click="exchangeLogRegModal('register')">注册</button>
                  <button class="reg" @click="exchangeLogRegModal('login')">登录</button>
                </div>
                <div v-if="AuthInfo.access_token" class="userAccount">
                  欢迎您：{{ AuthInfo.user_name }}<span @click="logout"> [注销]</span>
                </div>
            </div>
            <ul class="nav-list clearfix">
                <router-link tag="li"
                    :to="{path: '/'}"
                    :class="{'active': selectedNav=='HomePage'}">
                    首页
                 </router-link>
                <router-link tag="li"
                    :to="{path: '/MobileGameList'}"
                    :class="{'active': selectedNav=='MobileGameList'}">
                    手机游戏
                </router-link>
                <router-link tag="li"
                    :to="{path: '/servicer'}"
                    :class="{'active': selectedNav=='servicer'}">
                    客服中心
                 </router-link>
                <router-link tag="li" v-if="AuthInfo.access_token"
                    :to="{path: '/userCenter/uAdmin'}"
                    :class="{'active': selectedNav=='uAdmin' || selectedNav=='modifyPwd' ||
                    selectedNav=='bindTel' || selectedNav=='nameAuth'}">
                    个人中心
                 </router-link>
            </ul>
        </div>
    </div>
</template>
<script>
import { mapState, mapMutations, mapActions } from 'vuex'

export default {
    name: 'headNav',
    components: {

    },
    data() {
        return {
            selectedNav: 'home'
        }
    },
    computed: {
      ...mapState([
        'userInfo',
        'AuthInfo'
      ])
    },
    methods: {
      ...mapMutations([
        'exchangeLogRegModal',
        'handleLogout'
      ]),
      ...mapActions([
        'tipModalAct'
      ]),
      exchangeSelected(selected) {
          this.selectedNav = selected;
      },
      handleLogRegModal(type) {
        this.exchangeLogRegModal(type);
      },
      handleAuthLogin() {
        let auth_url = 'http://m.1aiyouxi.com/oauth/authorize?bn_auth_url=';
        window.location.href = auth_url + window.location.href;
      },
      logout() {
        this.storejs.set('user', '');
        this.handleLogout();
        this.tipModalAct({msg: '注销成功！', type: 'success'})
      }
    },
    mounted() {
      // console.log(this.$route);
      this.selectedNav = this.$route.name;

    }

}
</script>
<style lang="scss">
.head-nav {
    width: 100%;
    height: 80px;
    background: #fff;
    background: rgba(255,255,255,.8);
    z-index: 9999;
    min-width: 1200px;
    position: fixed;
    top: 0;
    left: 0;
    .head-box {
        position: relative;
        width: 1200px;
        margin: 0 auto;
        height: 80px;
        overflow: visible;
        .logo{
            width: 189px;
            height: 55px;
            background: url('../../assets/BNLOGO.png') no-repeat;
            background-size: 100% 100%;
            position: absolute;
            top: 12px;
            left: 0;
            cursor: pointer;
        }
        .nav-list{
            // margin: 0 auto;
            // display: block;
            // padding-left: 480px;
            float: right;
            li{
                float: left;
                list-style: none;
                font-size: 16px;
                width: 100px;
                height: 78px;
                line-height: 78px;
                text-align: center;
                cursor: pointer;
                margin: 0 25px;
                color: #000000;
                transition: color 0.3s linear;
            }
            li:hover{
              color: #31aae2;
              transition: color 0.3s linear;
            }
            li:first-child{
                margin-left: 0;
            }
            .active{
                border-bottom: 2px solid #31aae2;
                color: #31aae2;
            }
        }
        .login-reg{
          font-size: 16px;
          color: #ffffff;
          float: right;
          margin-left: 30px;
          @mixin login-reg{
              width: 67px;
              height: 30px;
              background: #31aae2;
              border: none;
              color: #ffffff;
              border-radius: 5px;
              cursor: pointer;
              margin: 25px 10px 25px 0;
              // transition: background 0.3s linear;
          }
          .login{
              @include login-reg;
          }
          .reg{
              @include login-reg;
          }
          .login:hover{
            background: #f4b122;
            // transition: background 0.3s linear;
          }
          .reg:hover{
            background: #f4b122;
            // transition: background 0.3s linear;
          }
          .userAccount{
            line-height: 80px;
            font-size: 16px;
            color: #000000;
            span{
              color: #8d8d8d;
              font-size: 12px;
              margin-left: 10px;
              cursor: pointer;
            }
            span:hover{
              color: #31aae2;
            }
          }
        }
    }
}
</style>

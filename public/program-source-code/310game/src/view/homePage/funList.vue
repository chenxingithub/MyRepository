<template>
    <div class="fun-list">
        <div class="list-title">
            <span>功能列表</span>
        </div>
        <div class="list-con">
            <ul>
                <li>
                    <div class="list-con-item reg" @click="handleBindTel('realName')">
                        <div class="item-img-box">
                            <!-- <img src="" alt="reg"> -->
                        </div>
                        <div>
                            <p class="item-tit">实名认证</p>
                            <p class="item-dec">冰鸟玩家个人中心</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="list-con-item bind-tel" @click="handleBindTel('bindTel')">
                        <div class="item-img-box">
                            <!-- <img src="" alt="reg"> -->
                        </div>
                        <div>
                            <p class="item-tit">绑定手机</p>
                            <p class="item-dec">冰鸟玩家个人中心</p>
                        </div>
                    </div>
                </li>
                <li>
                    <router-link to="/servicer" tag="div" class="list-con-item servicer">
                        <div class="item-img-box">
                            <!-- <img src="" alt="reg"> -->
                        </div>
                        <div>
                            <p class="item-tit">客服中心</p>
                            <p class="item-dec">冰鸟玩家服务中心</p>
                        </div>
                    </router-link>
                </li>
            </ul>
        </div>
    </div>
</template>
<script>
import { mapState,mapMutations } from 'vuex'

export default {
    name: 'funList',
    computed: {
      ...mapState(['AuthInfo'])
    },
    methods: {
      ...mapMutations(['exchangeLogRegModal']),
      handleBindTel(type) {
        var token = this.storejs.get('user');

        if(this.AuthInfo && this.AuthInfo.hasOwnProperty('access_token') && this.AuthInfo.access_token) {

          if(type == "realName") {
            this.$router.push('/userCenter/nameAuth');
          }
          if(type == "bindTel") {
            this.$router.push('/userCenter/bindTel');
          }
        } else {
          //未登录，跳统一登录
          // alert('跳统一登录！');
          this.exchangeLogRegModal('login');
        }
      }
    }
}
</script>
<style lang="scss">
.fun-list{
    $fun-h: 295px;
    $title-h: 57px;//高度

    width: 100%;
    height: $fun-h;
    box-sizing: border-box;
    // background: rgba(202, 172, 250, 0.45);
    .list-title{//功能列表标题
        $pdtop: 24px;//padding-top
        height: $title-h - $pdtop;
        width: 100%;
        font-size: 18px;
        padding-top: $pdtop;
        border-bottom: 1px solid #ebebeb;
        position: relative;
        span{
            margin-left: 38px;
        }
        span:before{
            height: 20px;
            width: 5px;
            content: " ";
            background: #31aae2;
            position: absolute;
            top: $pdtop;
            left: 20px;
        }
    }
    .list-con{
        box-sizing: border-box;
        width: 100%;
        height: $fun-h - $title-h;
        padding: 0 21px;
        li{
            list-style: none;
            height: 75px;
            border-bottom: 1px solid #ebebeb;
        }
        .list-con-item{
            width: 100%;
            height: 40px;
            padding: 17px 0;
            padding-left: 20px;
            cursor: pointer;
            >div{
                user-select: none;
                -ms-user-select: none;
                -webkit-user-select: none;
                -moz-user-select: none;
                float: left;
            }
            p{
                margin-top: 2px;
            }
            .item-tit{
                font-size: 16px;
                transition: color ease-in 0.18s;
            }
            .item-dec{
                font-size: 14px;
                color: #8d8d8d;
            }
            .item-img-box{
                height: 40px;
                width: 40px;
                margin-right: 14px;
            }
        }
        .list-con-item:hover{
          .item-tit{
            color: #31aae2;
            transition: color ease-in 0.18s;
          }
        }
        .reg{
            .item-img-box{
                background: url('../../assets/user-real-name-icon.png') no-repeat;
                background-size: 100% 100%;
            }
        }
        .bind-tel{
            .item-img-box{
                background: url('../../assets/bind-tel-icon.png') no-repeat;
                background-size: 100% 100%;
            }
            p{
              color: #000000;
            }
        }
        .servicer{
            .item-img-box{
                background: url('../../assets/servicer-icon.png') no-repeat;
                background-size: 100% 100%;
            }
        }
    }
}
</style>

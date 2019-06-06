<template>
  <div id="user-nav">
    <ul>
      <router-link to="uAdmin" tag="li" :class="{active: this.$route.name == 'uAdmin'}">
        <div @mouseenter="handleHoverStyle(0, $event)" @mouseout="handleMouseOutStyle">
          <div class="nav-icon">
            <img src="../../assets/user-admin-icon.png" v-if="this.$route.name != 'uAdmin'" alt="user">
            <img src="../../assets/user-admin-selected.png" v-if="this.$route.name == 'uAdmin'" alt="user">
          </div>
          我的主页
        </div>
      </router-link>
      <router-link to="modifyPwd" tag="li" :class="{active: this.$route.name == 'modifyPwd'}">
        <div @mouseenter="handleHoverStyle(1, $event)" @mouseout="handleMouseOutStyle">
          <div class="nav-icon">
            <img src="../../assets/user-pwd-icon.png" v-if="this.$route.name != 'modifyPwd'" alt="user">
            <img src="../../assets/user-pwd-sel-icon.png" v-if="this.$route.name == 'modifyPwd'" alt="user">
          </div>
          修改密码
        </div>
      </router-link>
      <router-link to="bindTel" tag="li" :class="{active: this.$route.name == 'bindTel'}">
        <div @mouseenter="handleHoverStyle(2, $event)" @mouseout="handleMouseOutStyle">
          <div class="nav-icon">
            <img src="../../assets/bindTelIcon.png" v-if="this.$route.name != 'bindTel'" alt="user">
            <img src="../../assets/bindTelSelIcon.png" v-if="this.$route.name == 'bindTel'" alt="user">
          </div>
          绑定手机
        </div>
      </router-link>
      <router-link to="nameAuth" tag="li" :class="{active: this.$route.name == 'nameAuth'}">
        <div @mouseenter="handleHoverStyle(3, $event)" @mouseout="handleMouseOutStyle">
          <div class="nav-icon">
            <img src="../../assets/auth-real-name-icon.png" v-if="this.$route.name != 'nameAuth'" alt="user">
            <img src="../../assets/auth-real-name-sel-icon.png" v-if="this.$route.name == 'nameAuth'" alt="user">
          </div>
          实名认证
        </div>
      </router-link>
    </ul>
    <span :style="{top: arrow_y + 'px'}"></span>
  </div>
</template>
<script>
export default {
  name: 'userNav',
  data() {
    return {
      arrow_y: 23,
      routeName: this.$route.name,
      routeNames: ['uAdmin', 'modifyPwd', 'bindTel', 'nameAuth']
    }
  },
  methods: {
    handleHoverStyle(index, e) {
      let li_height = 60;
      this.arrow_y = (index * li_height) + 23;
      // console.log('1111');
    },
    handleMouseOutStyle() {
      let li_height = 60;
      this.arrow_y = (this.routeNames.indexOf(this.$route.name) * li_height) + 23;
    }
  },
  // beforeRouteUpdate (to, from, next) {
  //   // this.routeName = to.name;
  //   console.log(to);
  //   next();
  // }
}
</script>
<style lang="scss">
#user-nav{
  position: relative;
  >ul{
    li{
      list-style: none;
      height: 59px;
      line-height: 59px;
      width: 100%;
      cursor: pointer;
      color: #656565;
      transition: color 0.3s linear;
      border-bottom: 1px solid #ebebeb;
      user-select: none;
      .nav-icon{
        display: inline-block;
        text-align: center;
        width: 63px;
      }
      img{
        vertical-align: middle;
      }
    }
    li:hover{
      color: #31aae2;
      transition: color 0.3s linear;
    }
    .active{
      color: #31aae2;
    }
  }
  span{
    width: 13px;
    height: 19px;
    position: absolute;
    top: 23px;
    right: -8px;
    transition: top 0.3s linear;
    background: url('../../assets/user-nav-selected.png') no-repeat;
    background-size: 100% 100%;
  }
}
</style>

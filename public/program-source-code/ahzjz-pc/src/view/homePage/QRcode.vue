<template>
  <div class="QRcode border-box">
    <div class="QRcode-box">
      <div class="QRcode-item1">
        <img src="../../assets/images/weixin_icon.png" alt="weixin">
        <p>
          <span>关注官方公众号</span>
        </p>
        <div class="item-box">
          <img src="../../assets/images/weinxin-server.jpg" alt="icon">
        </div>
      </div>
      <div class="QRcode-item1">
        <img src="../../assets/images/weixin_icon.png" alt="weixin">
        <p>
          <span>微信扫描进入论坛</span>
        </p>
        <div class="item-box">
          <img src="../../assets/images/bbs-code.png" alt="icon">
        </div>
      </div>
      <div class="QRcode-item1">
        <img src="../../assets/images/weixin_icon.png" alt="weixin">
        <p>
          <span>下载游戏</span>
        </p>
        <div class="item-box" v-if="baseInfo">
          <img :src="imgDomain+'/'+baseInfo.android_dow_code_img" alt="icon">
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { mapState } from 'vuex'

export default {//首页二维码
  name: 'QRcode',
  computed: {
    ...mapState([
      'baseInfo',
      'imgDomain'
    ])
  }
}
</script>
<style lang="scss">
.QRcode{
  width: 100%;
  height: 334px;
  @include bg('../../assets/images/erweima-bg.jpg', 1920px, 334px, center, center);
  padding: 35px 0;
  .QRcode-box{
    width: 860px;
    height: 264px;
    margin: 0 auto;
    // background: rgba(106, 171, 236, 0.3);
    >div{
      width: 160px;
      height: 264px;
      float: left;
      user-select: none;
      >img{
        margin-left: 120px;
      }
      p{
        margin-top: 6px;
      }
      span{
        color: #ffffff;
        font-size: 18px;
        position: relative;
        margin-left: 20px;
      }
      span:before{
        content: '';
        width: 1px;
        height: 20px;
        position: absolute;
        top: 3px;
        left: -19px;
        background: #970e0f;
      }
    }
    .QRcode-item1:nth-child(2){
      margin: 0 189px;
    }
    .item-box{
      width: 167px;
      height: 160px;
      margin: 17px 0 0 0;
      text-align: center;
      @include bg('../../assets/images/wx-bg.png');
      >img{
        width: 147px;
        height: 147px;
        margin-top: 6px;
      }
    }
  }
}
</style>

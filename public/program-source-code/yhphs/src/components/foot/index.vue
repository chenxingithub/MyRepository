<template>
  <div class="foot-nav">
    <!-- gameFeatures -->
    <div class="foot-box flex-sb-c">
      <a href="javascript:;">
        <img src="../../assets/images/home/scan-icon.png" alt="scan">
        关注公众号
      </a>
      <a href="javascript:;">
        <img src="../../assets/images/home/share-icon.png" alt="share">
        分享小伙伴
      </a>
      <a href="javascript:;">
        <img src="../../assets/images/home/mouse-icon.png" alt="share">
        进入电脑版
      </a>
    </div>
    <div class="foot-copyright">
      <a href="javascript:;" @click="handleToTop" class="to-top"></a>
      <p>广州冰鸟网络科技有限公司 粤ICP备 16094894 号-3</p>
      <p>COPYRIGHT @2016 ALLRIGHTS RESERVED</p>
      <p>地址：广州市天河区天河路490号壬丰大厦4019</p>
      <p>联系电话： 020-38915471 客服电话：020-38915471</p>
    </div>
  </div>
</template>
<script>
export default {
  name: 'footNav',
  props: ['toTop'],
  methods: {
    handleToTop() {
      if(this.toTop) {
        this.toTop();
      }
    }
  }
}
</script>
<style lang="scss">
.foot-nav{
  height: 463px;
  background: #000000;
  .foot-box{
    height: 170px;
    width: 750px;
    background: #161618;
    font-size: 24px;
    padding: 0 30px;
    a{
      color: #665f54;
    }
    img{
      width: 60px;
      height: 60px;
    }
  }
  .foot-copyright{
    position: relative;
    padding: 20px 22px 0;
    color: #665f54;
    font-size: 24px;
    width: 750px;
    height: 290px;

    .to-top{
      display: block;
      position: absolute;
      top: 98px;
      right: 25px;
      width: 99px;
      height: 99px;
      background: url('../../assets/images/home/to-top-icon.png') no-repeat;
      background-size: 100% 100%;
    }
  }
}
</style>

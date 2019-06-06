<template>
  <div class="head-nav flex-sb-c" v-if="status == 'show'">
    <div class="flex-sb-c">
      <router-link :to="{path: '/'}" class="icon" v-if="baseInfo"
      :style="{'background-image': 'url('+ imgDomain + baseInfo.logo_url +')'}">
      </router-link>
      <div class="tit">
        <p>永恒破坏神</p>
        <p>极致西方幻想 MMO</p>
      </div>
    </div>
    <a :href="downloadUrl" class="download"></a>
  </div>
</template>
<script>
import { mapState, mapGetters } from 'vuex'

export default {
  name: 'headNav',
  data() {
    return {
      status: 'hide'
    }
  },
  computed: {
    ...mapState(['baseInfo','imgDomain']),
    ...mapGetters(['downloadUrl'])
  },
  mounted() {
    this.$nextTick(() => {
      this.status = 'show';
    });
  }
}
</script>
<style lang="scss">
.head-nav{
  width: 750px;
  height: 100px;
  padding: 0 20px;
  position: fixed;
  top: 0;
  left: 50%;
  margin-left: -375px;
  z-index: 9000;
  background-color: #222222;
  .icon{
    width: 103px;
    height: 103px;
    margin-top: 18px;
    background-size: 100% 100%;
    background-position: center;
  }
  .tit{
    margin-left: 17px;
    p{
      margin-block-start: 0;
      margin-block-end: 0;
    }
    p:nth-child(1){
      color: #ffffff;
      font-weight: 600;
      font-size: 34px;
    }
    p:nth-child(2){
      margin-block-start: 8px;
      color: #949292;
      font-size: 24px;
    }
  }
  .download{
    width: 238px;
    height: 71px;
    display: block;
    background: url('../../assets/images/home/join-now.png') no-repeat;
    background-size: 100% 100%;
  }
}
</style>

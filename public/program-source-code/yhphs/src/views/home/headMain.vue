<template>
  <div class="head-main">
    <!-- head-main -->
    <div class="v-btn" @click="handleVideo('show')"></div>
    <div class="next-arrow"></div>
    <a :href="downloadUrl" class="download"></a>
  </div>
</template>
<script>
import { mapState, mapMutations, mapGetters } from 'vuex'

export default {
  name: 'headMain',
  computed: {
    ...mapState(['baseInfo']),
    ...mapGetters(['downloadUrl'])
  },
  methods: {
    ...mapMutations(['handleVideoModal']),
    handleVideo(type) {
      if(!this.baseInfo.video_url) {
        alert('敬请期待！');
      }
      this.handleVideoModal({status: type, url: this.baseInfo.video_url});
    },
  }
}
</script>
<style lang="scss">
.head-main{
  .v-btn{
    position: absolute;
    bottom: 465px;
    left: 50%;
    width: 80px;
    height: 80px;
    margin-left: -40px;
  }
  height: 100%;
  .download{
    $w: 460px;
    display: block;
    position: absolute;
    bottom: 75px;
    left: 50%;
    width: $w;
    height: 130px;
    margin-left: -$w/2;
  }
}
</style>

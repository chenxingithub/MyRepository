<template>
  <div class="video-cover" id="video-modal"
    v-if="videoUrl" @click.self="handleModal">
    <div class="video-content">
      <video controls autoplay poster="../../assets/images/home/news-slide.png">
        <source :src="videoUrl" type="video/mp4">
      </video>
      <div class="v-close" @click="handleModal"></div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'videoModal',
  methods: {
    handleModal(style) {
      this.$emit('handle-video', 'hide');
    }
  },
  props: {
    videoUrl: String
  }
}
</script>
<style lang="scss" scoped>
.video-cover{
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background: rgba(0, 0, 0, 0.65);
    z-index: 99999;
}
.video-content{
    position: absolute;
    top: 48%;
    left: 50%;
    transform: translate(-50%,-50%);
    /* border-radius: 5%; */
    font-size: 28px;
    color: #ffffff;
    text-align: center;
}
#video-modal{
  video{
    width: 750px;
    background: #000000;
  }
  .v-close{
    $w: 100px;
    position: absolute;
    bottom: -120px;
    left: 50%;
    margin: 0 0 0 (-$w/2);
    width: $w;
    height: $w;
    background: url('../../assets/images/common/videoClose.png') no-repeat;
    background-size: $w $w;
  }
}
</style>
